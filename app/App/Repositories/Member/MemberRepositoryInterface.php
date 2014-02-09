<?php namespace App\Repositories\Member;

interface MemberRepositoryInterface {

    /**
     * Get all members
     */
    public function getAll();

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
}