<?php namespace App\Repositories;

use App\Models\MemberGroup;

class DbMemberGroupRepository extends DbBaseRepository implements MemberGroupRepositoryInterface {

    protected $model;
    protected $orderBy = 'id';
    protected $orderDirection = 'asc';
    protected $perPage = 15;

    public function __construct(MemberGroup $model)
    {
        parent::__construct($model);
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
}