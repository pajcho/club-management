<?php namespace App\Modules\Members\Controllers;

use App\Controllers\AdminController;
use App\Modules\Members\Repositories\MemberGroupRepositoryInterface;
use App\Modules\Users\Repositories\UserRepositoryInterface;
use App\Service\Theme;
use Carbon\Carbon;
use Dingo\Api\Dispatcher;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Webpatser\Sanitize\Sanitize;

class MemberGroupController extends AdminController {

    private $memberGroups;
    private $users;
    /**
     * @var \Dingo\Api\Dispatcher
     */
    private $api;

    public function __construct(Dispatcher $api, MemberGroupRepositoryInterface $memberGroups, UserRepositoryInterface $users)
	{
		parent::__construct();

        View::share('activeMenu', 'groups');

        $this->memberGroups = $memberGroups;
        $this->users = $users;
        $this->api = $api;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $input = Input::get();

        $memberGroups = $this->api->with($input)->get('groups');

        return View::make(Theme::view('group.index'))->with(compact('memberGroups'));
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $input = Input::get();

        $filters = $this->api->with($input)->get('groups/filters');
        $users = $filters['users'];

		return View::make(Theme::view('group.create'))->with(compact('users'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        try
        {
            $this->api->with(Input::get())->post('groups');
        }
        catch(StoreResourceFailedException $e)
        {
            // validation failed
            return Redirect::route('group.create')->withInput()->withErrors($e->getErrors());
        }

        // Create redirect depending on submit button
        $redirect = Redirect::route('group.index');

        if(Input::get('create_and_add', false))
            $redirect = Redirect::route('group.create');

        return $redirect->withSuccess('Member group created!');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $input = Input::get();

        $memberGroup = $this->api->get('groups/' . $id);
        $filters = $this->api->with($input)->get('groups/filters');
        $users = $filters['users'];

        return View::make(Theme::view('group.update'))->with(compact('memberGroup', 'users'));
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
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        try
        {
            $this->api->with(Input::get())->put('groups/' . $id);
        }
        catch(UpdateResourceFailedException $e)
        {
            // validation failed
            return Redirect::route('group.show', $id)->withInput()->withErrors($e->getErrors());
        }

        return Redirect::route('group.show', $id)->withSuccess('Details updated!');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        try
        {
            $this->api->delete('groups/' . $id);
        }
        catch(DeleteResourceFailedException $e)
        {
            return Redirect::back()->withInput()->withError($e->getMessage());
        }

        return Redirect::back()->withInput()->withSuccess('Member group deleted!');
	}

    /**
     * Generate attendance PDF document
     *
     * @param  int $id
     * @param $year
     * @param $month
     * @param bool $download
     * @return Response
     */
    public function attendance($id, $year, $month, $download = false)
    {
        $pdf = App::make('App\Service\Pdf\PhantomPdf');
        $membersRepo = App::make('App\Modules\Members\Repositories\MemberRepositoryInterface');
        $memberGroup = $this->memberGroups->find($id);

        // Get all active group members
        $members = $membersRepo->filter(array(
            'group_id'      => $memberGroup->id,
            'subscribed'    => array('<=', Carbon::createFromDate($year, $month)->endOfMonth()->toDateTimeString()),
            'orderBy'       => array('dos' => 'asc'),
        ), false);

        // Get only members active in this month
        $members = $members->filter(function($member) use ($year, $month){
            return $member->activeOnDate($year, $month);
        })->values();

        $view = View::make(Theme::view('group.attendance'))->with(compact('memberGroup', 'members', 'year', 'month'))->render();
        $documentName = Sanitize::string($memberGroup->name . ' ' . (Lang::has('members::documents.attendance.title') ? Lang::get('members::documents.attendance.title') : 'Monthly group attendance list') . ' ' . $year . ' ' . $month);

        return $download ? $pdf->download($view, $documentName) : $pdf->stream($view, $documentName);
    }

    /**
     * Generate payments PDF document
     *
     * @param  int $id
     * @param $year
     * @param $month
     * @param bool $download
     * @return Response
     */
    public function payments($id, $year, $month, $download = false)
    {
        $pdf = App::make('App\Service\Pdf\PhantomPdf');
        $membersRepo = App::make('App\Modules\Members\Repositories\MemberRepositoryInterface');
        $memberGroup = $this->memberGroups->find($id);

        $firstMonth = (int) Config::get('settings.season_starts', 9);
        $lastMonth = (int) Config::get('settings.season_ends', 6);

        // Generate date limit
        $subscribedBefore = Carbon::createFromDate($year, $lastMonth);
        if($month > $lastMonth) $subscribedBefore = $subscribedBefore->addYear();

        // Get all active group members
        $members = $membersRepo->filter(array(
            'group_id'          => $memberGroup->id,
            'subscribed'        => array('<=', $subscribedBefore->endOfMonth()->toDateTimeString()),
            'orderBy'           => array('dos' => 'asc'),
        ), false);

        // Define what months numbers to show in this list
        $months = $this->generateMonthsRange($firstMonth, $lastMonth, $subscribedBefore->year);

        // Get only members active in this season
        $members = $members->filter(function($member) use ($months){
            return $member->activeInRange(
                head($months), head(array_keys($months)),
                last($months), last(array_keys($months)),
                $member->active
            );
        })->values();

        $view = View::make(Theme::view('group.payments'))->with(compact('memberGroup', 'members', 'months', 'year', 'month', 'firstMonth', 'lastMonth'))->render();
        $documentName = Sanitize::string($memberGroup->name . ' ' . (Lang::has('members::documents.payments.title') ? Lang::get('members::documents.payments.title') : 'Group payments') . ' ' . $year . ' ' . $month);

        return $download ? $pdf->download($view, $documentName) : $pdf->stream($view, $documentName);
    }

    protected function generateMonthsRange($firstMonth, $lastMonth, $year)
    {
        // Make sure we have numeric values for everything
        $firstMonth = (int) $firstMonth;
        $lastMonth = (int) $lastMonth;
        $year = (int) $year;

        // Make sure we don't have same values
        // If that happens we will make lastMonth to be smaller than firstMonth
        if($firstMonth == $lastMonth) $lastMonth = $lastMonth - 1;

        if($firstMonth < $lastMonth)
        {
            // Results are in same year
            foreach(range($firstMonth, $lastMonth) as $month) $return[$month] = $year;
        }
        else
        {
            // Results are in two different years
            // Results are in same year
            foreach(range($firstMonth, 12) as $month) $return[$month] = $year-1;
            foreach(range(1, $lastMonth) as $month) $return[$month] = $year;
        }

        return $return;
    }
}
