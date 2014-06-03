<?php namespace App\Modules\Members\Controllers;

use App\Controllers\AdminController;
use App\Service\Theme;
use Carbon\Carbon;
use Dingo\Api\Dispatcher;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class MemberGroupDetailsController extends AdminController {

    /**
     * @var \Dingo\Api\Dispatcher
     */
    private $api;

    public function __construct(Dispatcher $api)
	{
		parent::__construct();

        View::share('activeMenu', 'groups');

        $this->api = $api;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $memberGroupId
     * @return Response
     */
	public function index($memberGroupId)
	{
        $memberGroup = $this->api->get('groups/' . $memberGroupId);
        $months = $this->api->get('groups/' . $memberGroupId . '/details');
        $today = Carbon::now();

        return View::make(Theme::view('group.details.index'))->with(compact('memberGroup', 'months', 'today'));
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
        $memberGroup = $this->api->with(array('embeds' => 'details'))->get('groups/' . $memberGroupId);
        $members = $this->api->with(array(
            'group_id'          => $memberGroup->id,
            'subscribed'        => array('<=', Carbon::createFromDate($year, $month)->endOfMonth()->toDateTimeString()),
            'orderBy'           => array('dos' => 'asc'),
            'activeOnDate'      => array('year' => $year, 'month' => $month),
            'paginate'          => false,
        ))->get('members');

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
        $this->api->with(Input::all())->put('groups/' . $memberGroupId . '/details/' . $year . '/' . $month);

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
        //
	}
}
