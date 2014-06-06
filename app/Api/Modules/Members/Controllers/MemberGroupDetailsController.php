<?php namespace Api\Modules\Members\Controllers;

use Api\Controllers\ApiController;
use App\Modules\Members\Repositories\MemberGroupRepositoryInterface;
use Carbon\Carbon;
use Dingo\Api\Dispatcher;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Paginator;
use Illuminate\Support\Facades\Response;

class MemberGroupDetailsController extends ApiController {

    private $monthsPerPage;
    private $currentPage;
    private $memberGroups;
    protected $api;

	public function __construct(Dispatcher $api, MemberGroupRepositoryInterface $memberGroups)
	{
		parent::__construct();

        $this->monthsPerPage = 15;
        $this->currentPage = 1;
        $this->memberGroups = $memberGroups;
        $this->api = $api;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
	public function index()
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
     * @throws \Dingo\Api\Exception\ResourceException
     * @return Response
     */
	public function show($memberGroupId, $year, $month)
	{
        $memberGroup = $this->api->with(array('embeds' => 'details'))->get('groups/'.$memberGroupId);

        $details = $memberGroup->details()->where('year', $year)->where('month', $month)->first();

        // Details for this month don't exists
        if(!$details) App::abort(404);

        return $details;
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