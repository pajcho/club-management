<?php namespace App\Modules\Users\Repositories;

use App\Modules\Users\Models\UserGroupData;
use App\Repositories\DbBaseRepository;
use Illuminate\Support\Facades\Auth;

class DbUserGroupDataRepository extends DbBaseRepository implements UserGroupDataRepositoryInterface {

    protected $model;
    protected $columnNames;
    protected $orderBy = array('id' => 'asc');
    protected $perPage = 15;

    public function __construct(UserGroupData $model)
    {
        parent::__construct($model);
    }

    public function preReturnFilters()
    {
        parent::preReturnFilters();

        if($currentUser = $this->currentUser())
        {
            if($currentUser->isTrainer())
            {
                $this->model = $this->model->ownedBy($currentUser);
            }
        }
    }

    /**
     * Create or update member group data
     *
     * @param $id
     * @param $data
     */
    public function updateData($id, $data)
    {
        if($currentUser = $this->currentUser())
        {
            // Trainers can only update their own data
            // Admin users will be able to update all data
            if($currentUser->isTrainer() && ($currentUser->id != $data['user_id']))
            {
                return;
            }

            $data['group_id'] = $id;

            $dataToCheck = array_except($data, array('attendance'));

            $dataToInsert = $this->model->firstOrNew($dataToCheck);
            $dataToInsert->attendance = $data['attendance'];

            $dataToInsert->save();
        }
    }

    /**
     * Get currently logged in user from session
     *
     * @return mixed
     */
    public function currentUser()
    {
        return Auth::user();
    }
}