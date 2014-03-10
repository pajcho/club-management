<?php namespace App\Repositories;

interface SettingsRepositoryInterface extends BaseRepositoryInterface {

    public function saveSettings(array $data);

    public function getForConfig();

}