<?php namespace App\Repositories\Member;

use App\Models\Member;

class DbMemberRepository implements MemberRepositoryInterface {

    protected $perPage = 15;

    /**
     * Get all members
     * 
     * @return type
     */
    public function getAll()
    {
        return Member::all();
    }

    /**
     * Filter members
     */
    public function filter($params = array())
    {
        $members = new Member();

        // Filter by active status
        $active = array_get($params, 'active', false);

        if($active !== false)
        {
            $members = $members->where('active', '=', $active);
        }

        return $members->paginate($this->perPage);
    }

    /**
     * Get member by ID
     * 
     * @param type $id = Member ID
     * @return type
     */
    public function getById($id)
    {
        return Member::find((int)$id);
    }
    
    /**
     * Create new member
     * 
     * @param type $data = Input data
     */
    public function create($data)
    {
        Member::create($data);
    }
}