<?php namespace App\Repositories\MemberGroup;

use App\Models\MemberGroup;

class DbMemberGroupRepository implements MemberGroupRepositoryInterface {

    protected $perPage = 15;

    /**
     * Get all member groups
     * 
     * @return type
     */
    public function getAll()
    {
        return MemberGroup::all();
    }

    /**
     * Filter member groups
     */
    public function filter($params = array())
    {
        $memberGroups = new MemberGroup();

        foreach($memberGroups->getColumnNames() as $column)
        {
            if(isset($params[$column]))
            {
                $memberGroups = $memberGroups->where($column, '=', $params[$column]);
            }
        }

        return $memberGroups->paginate($this->perPage);
    }

    /**
     * Get member group by ID
     * 
     * @param type $id = Member group ID
     * @return type
     */
    public function getById($id)
    {
        return MemberGroup::find((int)$id);
    }
    
    /**
     * Create new member group
     * 
     * @param type $data = Input data
     */
    public function create($data)
    {
        MemberGroup::create($data);
    }

    /**
     * Get all group locations
     *
     * @return mixed
     */
    public function getAllLocations()
    {
        return MemberGroup::select('location')->distinct()->get();
    }

    /**
     * Get all member groups as array to use for select box
     */
    public function getForSelect()
    {
        return $this->getAll()->lists('name', 'id');
    }

    /**
     * Get all group locations as array to use for select box
     *
     * @return mixed
     */
    public function getLocationsForSelect()
    {
        return $this->getAllLocations()->lists('location', 'location');
    }

    /**
     * Delete member group
     *
     * @param $memberGroup
     * @return mixed
     */
    public function delete($memberGroup)
    {
        return $memberGroup->delete();
    }

    /**
     * Update member group
     *
     * @param $memberGroup
     * @param $data
     * @return mixed
     */
    public function update($memberGroup, $data)
    {
        return $memberGroup->update($data);
    }
}