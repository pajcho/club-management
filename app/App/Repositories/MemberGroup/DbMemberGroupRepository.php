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
}