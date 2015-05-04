<?php namespace App\Modules\Settings\Repositories;

use App\Repositories\BaseRepositoryInterface;

interface SettingsRepositoryInterface extends BaseRepositoryInterface {

    function saveSettings(array $data);

    function getForConfig();

}