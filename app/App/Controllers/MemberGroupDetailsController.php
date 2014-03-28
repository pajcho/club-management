<?php namespace App\Controllers;

use App\Repositories\MemberGroupRepositoryInterface;
use App\Service\Theme;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Paginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class MemberGroupDetailsController extends BaseController {

    private $monthsPerPage;
    private $currentPage;
    private $memberGroups;

	public function __construct(MemberGroupRepositoryInterface $memberGroups)
	{
		parent::__construct();

        View::share('activeMenu', 'groups');

        $this->monthsPerPage = 15;
        $this->currentPage = 1;
        $this->memberGroups = $memberGroups;
	}

    /**
     * Display a listing of the resource.
     *
     * @param $memberGroupId
     * @return Response
     */
	public function index($memberGroupId)
	{
		// Get all members
        $memberGroup = $this->memberGroups->find($memberGroupId);
        $months = $this->getEditableMonths();

        $months = Paginator::make(array_slice($months, (Input::get('page', $this->currentPage)-1) * $this->monthsPerPage, $this->monthsPerPage), count($months), $this->monthsPerPage);

        return View::make(Theme::view('group.details.index'))->with(compact('memberGroup', 'months'));
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
        $memberGroup = $this->memberGroups->findWith($memberGroupId, array('details'));

        $membersRepo = App::make('App\Repositories\MemberRepositoryInterface');

        // Get all active group members
        $members = $membersRepo->filter(array(
            'group_id'          => $memberGroup->id,
            'subscribed'        => array('<=', Carbon::createFromDate($year, $month)->endOfMonth()->toDateTimeString()),
            'orderBy'           => array('active' => 'desc', 'dob' => 'desc'),
//            'active'    => 1
        ), false);

        return View::make(Theme::view('group.details.update'))->with(compact('memberGroup', 'year', 'month', 'members'));
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
        $details['payment'] = Input::get('payment');
        $details['attendance'] = Input::get('attendance');

        $data = array(
            'year' => $year,
            'month' => $month,
            'details' => json_encode($details)
        );

        $this->memberGroups->updateDetails($memberGroupId, $data);

        // validation failed
        return Redirect::route('group.details.show', array($memberGroupId, $year, $month))->withSuccess('Group details updated!');
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
     * Get editable months for group
     *
     * @param int $startYear
     * @param int $startMonth
     * @internal param $data
     * @return array
     */
    private function getEditableMonths($startYear = 2009, $startMonth = 1)
    {
        $months = array();
        $start = Carbon::createFromDate($startYear, $startMonth);
        $today = Carbon::now();

        while($today->gte($start))
        {
            array_push($months, $today->copy());
            $today->subMonth();
        }

        return $months;

    }
    
}
