<?php namespace App\Modules\Users\Repositories;

use App\Repositories\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface {

    function getTypesForSelect();

}