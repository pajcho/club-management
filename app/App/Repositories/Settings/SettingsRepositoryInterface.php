<?php namespace App\Repositories\Settings;

interface SettingsRepositoryInterface {

    /**
     * Get all settings options
     */
    public function getAll();

    /**
     * Get all settings options as key => value
     * array to use as configuration values
     */
    public function getForConfig();

    /**
     * Get settings option by ID
     * 
     * @param type $id = Member group ID
     */
    public function getById($id);
    
    /**
     * Get settings option by name
     *
     * @param type $id = Member group ID
     */
    public function getByName($id);

    /**
     * Save settings options
     * 
     * @param type $data = Input data
     */
    public function update($data);

}