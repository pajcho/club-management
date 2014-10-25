<?php namespace App\Modules\Users\Repositories;

use App\Repositories\BaseRepositoryInterface;

interface UserGroupDataRepositoryInterface extends BaseRepositoryInterface {

    function updateData($id, $data);
}