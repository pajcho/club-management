<?php namespace App\Modules\Members\Repositories;

use App\Modules\Members\Models\MemberGroup;
use App\Modules\Members\Models\MemberGroupData;
use App\Repositories\DbBaseRepository;
use Illuminate\Support\Facades\Auth;

class DbMemberGroupRepository extends DbBaseRepository implements MemberGroupRepositoryInterface {

    protected $model;
    protected $columnNames;
    protected $orderBy = array('location' => 'asc', 'name' => 'asc');
    protected $perPage = 15;

    protected $modelData;

    public function __construct(MemberGroup $model, MemberGroupData $modelData)
    {
        parent::__construct($model);

        $this->modelData = $modelData;
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
        return $this->all()->lists('name', 'id');
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

        $this->model->find($id)->data()->save($dataToInsert);
    }
}