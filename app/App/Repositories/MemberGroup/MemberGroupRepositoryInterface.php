<?php namespace App\Repositories\MemberGroup;

interface MemberGroupRepositoryInterface {

    /**
     * Get all member groups
     */
    public function getAll();

    /**
     * Get all member groups as array to use for select box
     */
    public function getForSelect();

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

    /**
     * Get all group locations as array to use for select box
     *
     * @return mixed
     */
    public function getLocationsForSelect();

    /**
     * Delete member group
     *
     * @param $memberGroup
     * @return mixed
     */
    public function delete($memberGroup);

    /**
     * Update member group
     *
     * @param $memberGroup
     * @param $data
     * @return mixed
     */
    public function update($memberGroup, $data);
}