<?php namespace App\Repositories;

interface MemberGroupRepositoryInterface extends BaseRepositoryInterface {

    function getForSelect();

    function getLocationsForSelect();

    function canBeDeleted($id);

    function updateDetails($id, $data);
}