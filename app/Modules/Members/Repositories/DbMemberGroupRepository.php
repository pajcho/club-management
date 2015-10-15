<?php namespace App\Modules\Members\Repositories;

use App\Modules\Members\Models\MemberGroup;
use App\Modules\Members\Models\MemberGroupData;
use App\Modules\Users\Models\UserGroupData;
use App\Modules\Members\Repositories\MemberRepositoryInterface;
use App\Modules\Users\Repositories\UserRepositoryInterface;
use App\Modules\Users\Repositories\UserGroupDataRepositoryInterface;
use App\Modules\Members\Models\DateHistory;
use App\Repositories\DbBaseRepository;
use Illuminate\Support\Facades\Auth;

class DbMemberGroupRepository extends DbBaseRepository implements MemberGroupRepositoryInterface {

    protected $model;
    protected $columnNames;
    protected $orderBy = array('location' => 'asc', 'name' => 'asc');
    protected $perPage = 15;

    protected $modelData;
    protected $dateHistory;
    protected $members;
    protected $users;
    protected $userGroupData;

    public function __construct(MemberGroup $model, MemberGroupData $modelData, DateHistory $dateHistory,
                                MemberRepositoryInterface $members, UserRepositoryInterface $users,
                                UserGroupDataRepositoryInterface $userGroupData)
    {
        parent::__construct($model);

        $this->modelData = $modelData;
        $this->dateHistory = $dateHistory;
        $this->members = $members;
        $this->users = $users;
        $this->userGroupData = $userGroupData;
    }

    public function preReturnFilters()
    {
        parent::preReturnFilters();

        if($currentUser = Auth::user())
        {
            if($currentUser->isTrainer())
            {
                $this->model = $this->model->trainedBy($currentUser);
            }
        }
    }

    public function preDelete($item)
    {
        // Remove members from this group before deleting
        $item->members->each(function($member){
            $this->members->update($member->id, ['group_id' => 0]);
        });

        // Remove this group from all trainers
        $item->trainers->each(function($user) use ($item){
            $user->groups()->detach($item->id);
        });

        // Remove all date history items
//        $this->dateHistory->where('type', 'group_id')->where('value', $item->id)->delete();

        // Remove member group data
//        $this->modelData->where('group_id', $item->id)->delete();

        // Remove trainer group data
//        $this->userGroupData->filter(['group_id' => $item->id])->each(function($dataItem){
//            $dataItem->delete();
//        });
    }

    /**
     * We need to attach groups when creating user
     *
     * @param $input
     * @return mixed
     */
    public function create($input)
    {
        $group = $this->model->create($input);

        // Sync group trainers
        $this->syncTrainers($group, $input);

        return $group;
    }

    /**
     * We need to attach trainers when updating group
     *
     * @param $id
     * @param $input
     * @return mixed
     */
    public function update($id, $input)
    {
        $this->preReturnFilters();

        $group = $this->model->find($id);

        // Sync group trainers
        $this->syncTrainers($group, $input);

        return $group->update($input);
    }

    /**
     * Sync group trainers
     *
     * @param $group
     * @param $input
     */
    protected function syncTrainers($group, $input)
    {
        // Sync user groups
        $group->trainers()->sync(array_get($input, 'trainers', array()));
    }

    /**
     * Get all member groups as array to use for select box
     */
    public function getForSelect()
    {
        return ['0' => 'No Group'] + $this->all()->lists('name', 'id');
    }

    /**
     * Get all group locations as array to use for select box
     *
     * @return mixed
     */
    public function getLocationsForSelect()
    {
        return $this->model->select('location')->distinct()->get()->lists('location', 'location');
    }

    /**
     * Check if member group can be deleted
     * For now we prevent user to delete member group that has members
     *
     * @param $id
     * @return bool
     */
    public function canBeDeleted($id)
    {
        return $this->model->find($id)->members->count() ? false : true;
    }

    /**
     * Create or update member group data
     *
     * @param $id
     * @param $data
     */
    public function updateData($id, $data)
    {
        $data['group_id'] = $id;

        $dataToCheck = array_except($data, array('payed', 'attendance'));

        $dataToInsert = $this->modelData->firstOrNew($dataToCheck);
        $dataToInsert->payed = $data['payed'];
        $dataToInsert->attendance = $data['attendance'];

        $this->model->withTrashed()->find($id)->data()->save($dataToInsert);
    }
}
