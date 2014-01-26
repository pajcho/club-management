<?php namespace App\Repositories\Member;

class ArrayMemberRepository implements MemberRepositoryInterface {

    /**
     * Get all members
     * 
     * @return type
     */
	public function getAll()
	{
		return array(
            array(
                'id' => 1,
                'first_name' => 'Sonja',
                'Last_name' => 'Kocic',
            ),
            array(
                'id' => 2,
                'first_name' => 'Nikola',
                'Last_name' => 'Pajic',
            ),
        );
	}

    /**
     * Get member by ID
     * 
     * @param type $id = Member ID
     * @return type
     */
	public function getById($id)
	{
		$members = $this->getAll();
        
        foreach($members as $member)
        {
            if($member['id'] == $id)
            {
                return $member;
            }
        }
        
        return null;
	}
}