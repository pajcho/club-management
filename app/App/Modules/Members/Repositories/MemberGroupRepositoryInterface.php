<?php namespace App\Modules\Members\Repositories;

use App\Repositories\BaseRepositoryInterface;

interface MemberGroupRepositoryInterface extends BaseRepositoryInterface {

    function getForSelect();

    function getLocationsForSelect();

    function canBeDeleted($id);

    function updateData($id, $data);
}