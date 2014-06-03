<?php namespace App\Modules\Members\Repositories;

use App\Modules\Members\Models\MemberGroup;
use App\Modules\Members\Models\MemberGroupDetails;
use App\Repositories\DbBaseRepository;
use Illuminate\Support\Facades\Auth;

class DbMemberGroupRepository extends DbBaseRepository implements MemberGroupRepositoryInterface {

    protected $model;
    protected $columnNames;
    protected $orderBy = array('location' => 'asc', 'name' => 'asc');
    protected $perPage = 15;
    protected $allowEmbeds = array('details', 'members', 'trainers', 'history');

    protected $modelDetails;

    public function __construct(MemberGroup $model, MemberGroupDetails $modelDetails)
    {
        parent::__construct($model);

        $this->modelDetails = $modelDetails;
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
        $group = $this->model->create($this->prepareTimesForInsert($input));

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

        $group->update($this->prepareTimesForInsert($input));
        return $group;
    }

    /**
     * Sync group trainers
     *
     * @param $group
     * @param $input
     */
    protected function syncTrainers($group, $input)
    {
        if(isset($input['trainers']))
        {
            // Force trainers to be an array of values
            $trainers = array_get($input, 'trainers', array());
            $trainers = is_array($trainers) ? $trainers : array();
            // Sync group trainers
            $group->trainers()->sync($trainers);
        }
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
     * Create or update member group details
     *
     * @param $id
     * @param $data
     */
    public function updateDetails($id, $data)
    {
        $data['group_id'] = $id;

        $dataToCheck = array_except($data, array('details'));

        $details = $this->modelDetails->firstOrNew($dataToCheck);
        $details->details = $data['details'];

        $group = $this->model->find($id);
        $group->details()->save($details);

        return $group;
    }

    /**
     * Prepare array columns for database by converting them to JSON
     *
     * @param $data
     */
    protected function prepareTimesForInsert($data)
    {
        $convert = array('training');

        foreach($data as $key => $value)
        {
            if(is_array($value) && in_array($key, $convert)) $data[$key] = json_encode($value);
        }

        return $data;
    }
}