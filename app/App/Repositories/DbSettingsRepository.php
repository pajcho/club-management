<?php namespace App\Repositories;

use App\Models\Settings;

class DbSettingsRepository extends DbBaseRepository implements SettingsRepositoryInterface {

    protected $model;
    protected $orderBy = 'id';
    protected $orderDirection = 'asc';
    protected $perPage = 100;

    public function __construct(Settings $model)
    {
        parent::__construct($model);
    }

    /**
     * Save settings options
     *
     * @param array $data = Input data
     */
    public function saveSettings(array $data)
    {
        foreach($data as $id => $settings)
        {
            $this->model->find($id)->update($settings);
        }
    }

    /**
     * Get all settings options as key => value
     * array to use as configuration values
     */
    public function getForConfig()
    {
        return $this->model->all()->lists('value', 'key');
    }
}