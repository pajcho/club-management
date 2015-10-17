<?php namespace App\Modules\Users\Controllers;

use App\Http\Controllers\AdminController;
use App\Internal\EditableMonthsTrait;
use App\Modules\Members\Models\MemberGroupData;
use App\Modules\Members\Repositories\MemberGroupRepositoryInterface;
use App\Modules\Users\Repositories\UserGroupDataRepositoryInterface;
use App\Modules\Users\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class UserAttendanceController extends AdminController
{
    use EditableMonthsTrait;

    private $monthsPerPage;
    private $currentPage;
    private $users;
    private $userGroups;
    private $userGroupData;

    public function __construct(UserRepositoryInterface $users, UserGroupDataRepositoryInterface $userGroupData, MemberGroupRepositoryInterface $userGroups)
    {
        parent::__construct();

        $this->filterRequests();

        View::share('activeMenu', 'users');

        $this->monthsPerPage = 15;
        $this->currentPage   = 1;
        $this->users         = $users;
        $this->userGroups    = $userGroups;
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

        if (!$user) app()->abort(404);

        $months = $this->getEditableMonths(2014, 9);

        $months = new Paginator(array_slice($months, (Input::get('page', $this->currentPage)-1) * $this->monthsPerPage, $this->monthsPerPage), count($months), $this->monthsPerPage);
        $months->setPath('/' . Request::path());
        $today = Carbon::now();

        return view('user.attendance.index')->with(compact('user', 'months', 'today'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, $year, $month)
    {
        $user = $this->users->findWith($id, ['data']);

        if (!$user) app()->abort(404);

        $data = [];
        $userData = $user->data()->orderBy('group_id', 'desc')->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('created_at', 'desc')->get();
        $userGroups = $this->userGroups->orderBy('name', 'asc')->all();

        // Make data to be array of arrays instead of values
        foreach ($userGroups->all() as $group) $data[$group->id] = [
            'group'  => $group,
            'data'  => [],
        ];

        $today = Carbon::now()->year($year)->month($month);

        $monthData = $userData->filter(function ($userDataItem) use ($today) {
            return $userDataItem->year == $today->year && $userDataItem->month == $today->month;
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
                    'year'       => $today->year,
                    'month'      => $today->month,
                    'attendance' => json_encode([]),
                ]));
            }
        }

        return view('user.attendance.update')->with(compact('user', 'data', 'today'));
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

        return back()->withSuccess('User data updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $userId
     * @param $memberGroupId
     * @param $year
     *
     * @return Response
     */
    public function destroy($userId, $memberGroupId, $year)
    {
        $user = $this->users->findWith($userId, ['data']);

        if (!$user) app()->abort(404);

        $user->data()
            ->where('group_id', $memberGroupId)
            ->where('year', $year)
            ->delete();

        // Return message each time because why not :)
        return redirect(route('user.attendance.index', [$userId]))->withSuccess('User data deleted!');
    }

    /**
     * Filter user requests, because some actions
     * are only allowed to admin users
     */
    public function filterRequests()
    {
        if (Route::input('user')) {
            // Allow only admin users and owners to edit their profile
            $this->middleware('allowOnlyAdminOrCurrentUser', ['only' => ['index', 'update']]);
        }

        // Allow only admin users to do requests here
        $this->middleware('allowOnlyAdmin', ['except' => ['index', 'update']]);
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
}
