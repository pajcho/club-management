<?php namespace App\Repositories\Member;

interface MemberRepositoryInterface {

    /**
     * Get all members
     */
    public function getAll();

    /**
     * Filter members
     */
    public function filter($params = array());

    /**
     * Get member by ID
     * 
     * @param type $id = Member ID
     */
    public function getById($id);
    
    /**
     * Create new member
     * 
     * @param type $data = Input data
     */
    public function create($data);

    /**
     * Delete member
     *
     * @param $member
     * @return mixed
     */
    public function delete($member);

    /*
     * Update member
     *
     * @param $member
     * @param $data
     */
    public function update($member, $data);
}