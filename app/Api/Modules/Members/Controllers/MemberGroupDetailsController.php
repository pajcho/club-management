<?php namespace Api\Modules\Members\Controllers;

use Api\Controllers\ApiController;
use App\Modules\Members\Repositories\MemberGroupRepositoryInterface;
use App\Modules\Members\Repositories\MemberRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Paginator;
use Illuminate\Support\Facades\Response;

class MemberGroupDetailsController extends ApiController {

    private $monthsPerPage;
    private $currentPage;
    private $memberGroups;
    private $members;

	public function __construct(MemberGroupRepositoryInterface $memberGroups, MemberRepositoryInterface $members)
	{
		parent::__construct();

        $this->monthsPerPage = 15;
        $this->currentPage = 1;
        $this->memberGroups = $memberGroups;
        $this->members = $members;
	}

    /**
     * Display a listing of the resource.
     *
     * @param $memberGroupId
     * @return Response
     */
	public function index($memberGroupId)
	{
        $months = $this->getEditableMonths();
        $months = Paginator::make(array_slice($months, (Input::get('page', $this->currentPage)-1) * $this->monthsPerPage, $this->monthsPerPage), count($months), $this->monthsPerPage);

        return $months;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        //
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        //
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
        //
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

        return $this->memberGroups->updateDetails($memberGroupId, $data);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        //
	}

    /**
     * Get editable months for group
     *
     * @param int $startYear
     * @param int $startMonth
     * @internal param $data
     * @return array
     */
    private function getEditableMonths($startYear = 2013, $startMonth = 1)
    {
        $months = array();
        $start = Carbon::createFromDate($startYear, $startMonth);
        $end = Carbon::now()->addMonth();

        while($end->gte($start))
        {
            array_push($months, $end->copy());
            $end->subMonth();
        }

        return $months;
    }
}