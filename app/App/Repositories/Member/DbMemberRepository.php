<?php namespace App\Repositories\Member;

use App\Models\Member;

class DbMemberRepository implements MemberRepositoryInterface {

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
     * Get member by ID
     * 
     * @param type $id = Member ID
     * @return type
     */
	public function getById($id)
	{
		return Member::find((int)$id);
	}
}