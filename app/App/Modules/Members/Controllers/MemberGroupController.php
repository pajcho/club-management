<?php namespace App\Modules\Members\Controllers;

use App\Controllers\AdminController;
use App\Modules\Members\Internal\Validators\MemberGroupValidator;
use App\Modules\Members\Repositories\MemberGroupRepositoryInterface;
use App\Modules\Users\Repositories\UserRepositoryInterface;
use App\Service\Theme;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
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
        $users = $this->users->getForSelect();

		return View::make(Theme::view('group.create'))->with(compact('users'));
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
     * @param bool $download
     * @return Response
     */
    public function attendance($id, $year, $month, $download = false)
    {
        $pdf = App::make('App\Service\Pdf\PhantomPdf');
        $membersRepo = App::make('App\Modules\Members\Repositories\MemberRepositoryInterface');
        $memberGroup = $this->memberGroups->find($id);

        // Get all active group members
        $members = $membersRepo->filter([
            // We need to show old members that are now in new groups so we dont need this filter any more
            //'group_id'      => $memberGroup->id,
            'subscribed'    => ['<=', Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateTimeString()],
            'orderBy'       => ['dos' => 'asc'],
        ], false);

        // Get only members active in this month
        $members = $members->filter(function($member) use ($id, $year, $month){
            return $member->inGroupOnDate($id, $year, $month) && $member->activeOnDate($year, $month);
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
        $subscribedBefore = Carbon::createFromDate($year, $lastMonth, 1);
        if($month > $lastMonth) $subscribedBefore = $subscribedBefore->addYear();

        // Get all active group members
        $members = $membersRepo->filter([
            // We need to show old members that are now in new groups so we dont need this filter any more
            //'group_id'          => $memberGroup->id,
            'subscribed'        => ['<=', $subscribedBefore->endOfMonth()->toDateTimeString()],
            'orderBy'           => ['dos' => 'asc'],
        ], false);

        // Define what months numbers to show in this list
        $months = $this->generateMonthsRange($firstMonth, $lastMonth, $subscribedBefore->year);

        // Get only members active in this season
        $members = $members->filter(function($member) use ($id, $months){
            return $member->inGroupInRange(
                $id,
                head($months), head(array_keys($months)),
                last($months), last(array_keys($months)),
                $member->group_id == $id
            ) && $member->activeInRange(
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
        $cacheKey = implode('|', ['monthsRange', $firstMonth, $lastMonth, $year]);

        return Cache::rememberForever($cacheKey, function() use ($firstMonth, $lastMonth, $year){
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
        });
    }

    /**
     * Prepare array columns for database by converting them to JSON
     *
     * @param $data
     */
    private function prepareTimesForInsert($data)
    {
        $convert = ['training'];

        foreach($data as $key => $value)
        {
            if(is_array($value) && in_array($key, $convert)) $data[$key] = json_encode($value);
        }

        return $data;
    }
    
}
