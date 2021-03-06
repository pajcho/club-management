<?php namespace App\Modules\Members\Controllers;

use App\Http\Controllers\AdminController;
use App\Internal\EditableMonthsTrait;
use App\Modules\Members\Repositories\MemberGroupRepositoryInterface;
use App\Modules\Members\Repositories\MemberRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class MemberGroupDataController extends AdminController {

    use EditableMonthsTrait;

    private $monthsPerPage;
    private $currentPage;
    private $memberGroups;
    private $members;

	public function __construct(MemberGroupRepositoryInterface $memberGroups, MemberRepositoryInterface $members)
	{
		parent::__construct();

        View::share('activeMenu', 'groups');

        $this->monthsPerPage = 15;
        $this->currentPage = 1;
        $this->memberGroups = $memberGroups;
        $this->members = $members;
	}

    /**
     * Display a listing of the resource.
     *
     * @param           $memberGroupId
     *
     * @return Response
     */
	public function index($memberGroupId)
	{
		// Get all members
        $memberGroup = $this->memberGroups->find($memberGroupId);
        $months = $this->getEditableMonths();

        $months = new Paginator(array_slice($months, (Input::get('page', $this->currentPage)-1) * $this->monthsPerPage, $this->monthsPerPage), count($months), $this->monthsPerPage);
        $months->setPath('/' . Request::path());
        $today = Carbon::now();

        return view('group.data.index')->with(compact('memberGroup', 'months', 'today'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

	}

    /**
     * Display the specified resource.
     *
     * @param $memberGroupId
     * @param $year
     * @param $month
     * @return Response
     */
	public function show($memberGroupId, $year, $month)
	{
        $memberGroup = $this->memberGroups->findWith($memberGroupId, ['data']);

        // Get only ids of members that are or were in this group at some time
        // This will lower number of required database queries to do all necessary calculations
        $memberIds = $this->members->thatAreInGroupOnDate($memberGroupId, $year, $month);

        // Get all group members
        $members = $this->members->filter([
            'ids'        => $memberIds,
            'subscribed' => ['<=', Carbon::create($year, $month, 1)->endOfMonth()->toDateTimeString()],
            'orderBy'    => ['dos' => 'asc'],
        ], false);

        // Get only members active in this month
        $members = $members->filter(function($member) use ($memberGroupId, $year, $month){
            return $member->inGroupOnDate($memberGroupId, $year, $month) && $member->activeOnDate($year, $month);
        })->values();

        $highlight = Input::get('highlight', false);

        return view('group.data.update')->with(compact('memberGroup', 'year', 'month', 'members', 'highlight'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        //
	}

    /**
     * Update the specified resource in storage.
     *
     * @param $memberGroupId
     * @param $year
     * @param $month
     * @return Response
     */
	public function update($memberGroupId, $year, $month)
	{
        foreach(Input::get('data', []) as $memberId => $data)
        {
            $data = [
                'member_id'     => $memberId,
                'year'          => $year,
                'month'         => $month,
                'payed'         => array_get($data, 'payed', 0),
                'attendance'    => json_encode($data['attendance']),
            ];

            $this->memberGroups->updateData($memberGroupId, $data);

            // Clear related cache
            $tags = ['memberGroup', 'payedString', 'memberGroup:'.$memberGroupId, 'year:'.$year, 'month:'.$month];
            Cache::tags($tags)->forget(implode('|', $tags));
        }

        return redirect(route('group.data.show', [$memberGroupId, $year, $month]))->withSuccess('Group data updated!');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

	}

    /**
     * Should be only called as an AJAX call as it will only return content of one button
     *
     * @param $memberGroupId
     * @param $year
     * @param $month
     *
     * @return string
     */
    public function getPaymentData($memberGroupId, $year, $month)
    {
        $memberGroup = $this->memberGroups->findWith($memberGroupId, ['data']);

        return $memberGroup->data($year, $month) ? $memberGroup->payedString($year, $month) : '';
    }
}
