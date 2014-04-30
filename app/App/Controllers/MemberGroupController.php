<?php namespace App\Controllers;

use App\Internal\Validators\MemberGroupValidator;
use App\Repositories\MemberGroupRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Service\Theme;
use Carbon\Carbon;
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

	public function __construct(MemberGroupRepositoryInterface $memberGroups, UserRepositoryInterface $users)
	{
		parent::__construct();

        View::share('activeMenu', 'groups');

        $this->memberGroups = $memberGroups;
        $this->users = $users;
	}

    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Get all members
        $memberGroups = $this->memberGroups->filter(Input::get());
        
        return View::make(Theme::view('group.index'))->with('memberGroups', $memberGroups);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make(Theme::view('group.create'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $validator = new MemberGroupValidator();

        if ($validator->validate(Input::all(), 'create'))
        {
            // validation passed
            $this->memberGroups->create($this->prepareTimesForInsert($validator->data()));

            // Create redirect depending on submit button
            $redirect = Redirect::route('group.index');

            if(Input::get('create_and_add', false))
                $redirect = Redirect::route('group.create');


            return $redirect->withSuccess('Member group created!');
        }

        // validation failed
		return Redirect::route('group.create')->withInput()->withErrors($validator->errors());
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $memberGroup = $this->memberGroups->find($id);
        $users = $this->users->getForSelect();

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
        $validator = new MemberGroupValidator();

        if ($validator->validate(Input::all(), 'update', $id))
        {
            // validation passed
            $this->memberGroups->update($id, $this->prepareTimesForInsert($validator->data()));

            return Redirect::route('group.show', $id)->withSuccess('Details updated!');
        }

        // validation failed
        return Redirect::route('group.show', $id)->withInput()->withErrors($validator->errors());
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        // We can not delete group that has members
        if(!$this->memberGroups->canBeDeleted($id))
        {
            return Redirect::back()->withInput()->withError('Member group already has members! In order to delete this group first remove all members from it.');
        }

        $this->memberGroups->delete($id);

        return Redirect::back()->withInput()->withSuccess('Member group deleted!');
	}

    /**
     * Generate attendance PDF document
     *
     * @param  int $id
     * @param $year
     * @param $month
     * @return Response
     */
    public function attendance($id, $year, $month)
    {
        $pdf = App::make('App\Service\Pdf\PhantomPdf');
        $membersRepo = App::make('App\Repositories\MemberRepositoryInterface');
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
        $documentName = Sanitize::string($memberGroup->name . ' ' . (Lang::has('documents.attendance.title') ? Lang::get('documents.attendance.title') : 'Monthly group attendance list') . ' ' . $year . ' ' . $month);

        return $pdf->download($view, $documentName);
    }

    /**
     * Generate payments PDF document
     *
     * @param  int $id
     * @param $year
     * @param $month
     * @return Response
     */
    public function payments($id, $year, $month)
    {
        $pdf = App::make('App\Service\Pdf\PhantomPdf');
        $membersRepo = App::make('App\Repositories\MemberRepositoryInterface');
        $memberGroup = $this->memberGroups->find($id);

        $first_month = 9;
        $last_month = 6;

        // Generate date limit
        $subscribed_before = Carbon::createFromDate($year, $last_month);
        if($month > $last_month) $subscribed_before = $subscribed_before->addYear();

        // Get all active group members
        $members = $membersRepo->filter(array(
            'group_id'          => $memberGroup->id,
            'subscribed'        => array('<=', $subscribed_before->endOfMonth()->toDateTimeString()),
            'orderBy'           => array('dos' => 'asc'),
        ), false);

        // Get only members active in this month
//        $members = $members->filter(function($member) use ($year, $month){
//            return $member->activeOnDate($year, $month);
//        })->values();

        // Define what months numbers to show in this list
        $months = array(
            9 => Carbon::createFromDate($subscribed_before->year)->subYear()->year,
            10 => Carbon::createFromDate($subscribed_before->year)->subYear()->year,
            11 => Carbon::createFromDate($subscribed_before->year)->subYear()->year,
            12 => Carbon::createFromDate($subscribed_before->year)->subYear()->year,
            1 => Carbon::createFromDate($subscribed_before->year)->year,
            2 => Carbon::createFromDate($subscribed_before->year)->year,
            3 => Carbon::createFromDate($subscribed_before->year)->year,
            4 => Carbon::createFromDate($subscribed_before->year)->year,
            5 => Carbon::createFromDate($subscribed_before->year)->year,
            6 => Carbon::createFromDate($subscribed_before->year)->year,
        );

        $view = View::make(Theme::view('group.payments'))->with(compact('memberGroup', 'members', 'months', 'year', 'month', 'first_month', 'last_month'))->render();
        $documentName = Sanitize::string($memberGroup->name . ' ' . (Lang::has('documents.payments.title') ? Lang::get('documents.payments.title') : 'Group payments') . ' ' . $year . ' ' . $month);

        return $pdf->download($view, $documentName);
    }

    /**
     * Prepare array columns for database by converting them to JSON
     *
     * @param $data
     */
    private function prepareTimesForInsert($data)
    {
        $convert = array('training');

        foreach($data as $key => $value)
        {
            if(is_array($value) && in_array($key, $convert)) $data[$key] = json_encode($value);
        }

        return $data;
    }
    
}
