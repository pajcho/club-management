<?php namespace App\Modules\Users\Controllers;

use App\Controllers\AdminController;
use App\Internal\EditableMonthsTrait;
use App\Modules\Members\Models\MemberGroupData;
use App\Modules\Users\Repositories\UserGroupDataRepositoryInterface;
use App\Modules\Users\Repositories\UserRepositoryInterface;
use App\Service\Theme;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class UserAttendanceController extends AdminController
{

    use EditableMonthsTrait;

    private $users;
    private $userGroupData;

    public function __construct(UserRepositoryInterface $users, UserGroupDataRepositoryInterface $userGroupData)
    {
        parent::__construct();

        $this->filterRequests();

        View::share('activeMenu', 'users');

        $this->users = $users;
        $this->userGroupData = $userGroupData;
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function index($id)
    {
        $user = $this->users->findWith($id, ['data']);

        if (!$user) App::abort(404);

        $userData = $user->data()->orderBy('group_id', 'desc')->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('created_at', 'desc')->get();

        /**
         * Populate data with a list of group ids
         * If user has 3 groups result would be something like this:
         *
         *  array(
         *      12  => 'Group Name',
         *      5   => 'Another Group Name',
         *      18  => 'Group',
         *  )
         *
         * IMPORTANT: We don't allow for empty array, because if user has no groups than there is no attendance :)
         *
         */
        $data = $user->groups()->get()->lists('name', 'id');

        if ($data) {
            // Make data to be array of arrays instead of values
            foreach ($data as $key => $value) $data[ $key ] = [
                'name'  => $value,
                'years' => [],
                'data'  => [],
            ];

            $months = $this->getEditableMonths(2014, 9);

            foreach ($months as $month) {
                $monthData = $userData->filter(function ($userDataItem) use ($month) {
                    return $userDataItem->year == $month->year && $userDataItem->month == $month->month;
                });

                // Populate every group array with this new data
                foreach ($data as $groupId => $groupData) {
                    $monthDataFound = false;

                    foreach ($monthData->all() as $monthDataItem) {
                        if ($monthDataItem->group_id == $groupId) {
                            array_push($data[ $monthDataItem->group_id ]['data'], $monthDataItem);
                            $monthDataFound = true;
                        }
                    }

                    if (!$monthDataFound) {
                        array_push($data[ $groupId ]['data'], new MemberGroupData([
                            'group_id'   => $groupId,
                            'user_id'    => $user->id,
                            'year'       => $month->year,
                            'month'      => $month->month,
                            'attendance' => json_encode([]),
                        ]));
                    }
                }
            }

            // Array of available years to be used as tabs on this page
            foreach ($data as $groupId => $groupData) {
                foreach ($groupData['data'] as $item) {
                    if (!in_array($item->year, $data[ $groupId ]['years'])) array_push($data[ $groupId ]['years'], $item->year);
                }
            }
        }

        $data = $this->restructureData($data);

        return View::make(Theme::view('user.attendance'))->with(compact('user', 'data'));
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
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $userId
     *
     * @return Response
     */
    public function update($userId)
    {
        foreach (Input::get('data', []) as $details) {
            $this->saveGroupDetails($userId, $details);
        }

        return Redirect::route('user.attendance.index', [$userId])->withSuccess('User data updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Filter user requests, because some actions
     * are only allowed to admin users
     */
    public function filterRequests()
    {
        if ($userId = Route::input('user')) {
            // Allow only admin users and owners to edit their profile
            $this->beforeFilter('isAdminOr:' . $userId, ['only' => ['index', 'update']]);
        }

        // Allow only admin users to do requests here
        $this->beforeFilter('isAdminOr', ['except' => ['index', 'update']]);
    }

    /**
     * @param $userId
     * @param $details
     */
    private function saveGroupDetails($userId, $details)
    {
        foreach ($details['data'] as $userGroupId => $data) {
            $data = [
                'user_id'    => $userId,
                'year'       => $details['year'],
                'month'      => $details['month'],
                'attendance' => json_encode($data['attendance']),
            ];

            $this->userGroupData->updateData($userGroupId, $data);
        }
    }

    /**
     * @param $data
     *
     * @return array
     */
    private function restructureData($data)
    {
        $return = [];

        // Get all available years from all groups
        $years = [];
        foreach($data as $groupId => $details)
        {
            $years = array_merge($years, $details['years']);
        }

        $years = array_values(array_unique($years));
        ksort($years, SORT_DESC);

        // Populate array with group details sorted out by years
        foreach($years as $year)
        {
            foreach($data as $groupId => $details)
            {
                $return[$year][$groupId] = ['name' => $details['name'], 'data' => []];

                foreach($details['data'] as $groupDetails)
                {
                    if($groupDetails->year == $year){
                        array_push($return[$year][$groupId]['data'], $groupDetails);
                    }
                }
            }
        }
        // Remove empty results
        foreach($return as $year => $details)
        {
            foreach($details as $groupId => $data)
            {
                if(empty($data['data'])) unset($return[$year][$groupId]);
            }
        }

        // Remove empty years
        foreach($return as $year => $details)
        {
            if(empty($details)) unset($return[$year]);
        }

        return $return;
    }
}
