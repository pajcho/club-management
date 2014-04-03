<?php namespace App\Repositories;

use App\Models\MemberGroup;
use App\Models\MemberGroupDetails;

class DbMemberGroupRepository extends DbBaseRepository implements MemberGroupRepositoryInterface {

    protected $model;
    protected $orderBy = array('location' => 'asc', 'name' => 'asc');
    protected $perPage = 15;

    protected $modelDetails;

    public function __construct(MemberGroup $model, MemberGroupDetails $modelDetails)
    {
        parent::__construct($model);

        $this->modelDetails = $modelDetails;
    }

    /**
     * Get all member groups as array to use for select box
     */
    public function getForSelect()
    {
        return $this->model->all()->lists('name', 'id');
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

        $this->model->find($id)->details()->save($details);
    }
}