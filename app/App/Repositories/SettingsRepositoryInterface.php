<?php namespace App\Repositories;

interface SettingsRepositoryInterface extends BaseRepositoryInterface {

    function saveSettings(array $data);

    function getForConfig();

}