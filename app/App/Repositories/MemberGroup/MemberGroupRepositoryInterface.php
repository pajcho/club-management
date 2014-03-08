<?php namespace App\Repositories\MemberGroup;

interface MemberGroupRepositoryInterface {

    /**
     * Get all member groups
     */
    public function getAll();

    /**
     * Filter member groups
     */
    public function filter($params = array());

    /**
     * Get member group by ID
     * 
     * @param type $id = Member group ID
     */
    public function getById($id);
    
    /**
     * Create new member group
     * 
     * @param type $data = Input data
     */
    public function create($data);

    /**
     * Get all group locations
     *
     * @return mixed
     */
    public function getAllLocations();
}