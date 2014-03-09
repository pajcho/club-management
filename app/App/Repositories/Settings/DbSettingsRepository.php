<?php namespace App\Repositories\Settings;

use App\Models\Settings;

class DbSettingsRepository implements SettingsRepositoryInterface {

    /**
     * Get all settings options
     * 
     * @return type
     */
    public function getAll()
    {
        return Settings::all();
    }

    /**
     * Get settings option by ID
     * 
     * @param type $id = Settings option ID
     * @return type
     */
    public function getById($id)
    {
        return Settings::find((int)$id);
    }

    /**
     * Get settings option by name
     *
     * @param $name
     * @return type
     */
    public function getByName($name)
    {
        return Settings::where('key', '=', $name)->first();
    }

    /**
     * Save settings options
     * 
     * @param type $data = Input data
     */
    public function update($data)
    {
        foreach($data as $id => $settings)
        {
            Settings::find($id)->update($settings);
        }
    }

    /**
     * Get all settings options as key => value
     * array to use as configuration values
     */
    public function getForConfig()
    {
        return $this->getAll()->lists('value', 'key');
    }
}