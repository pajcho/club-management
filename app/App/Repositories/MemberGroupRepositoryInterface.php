<?php namespace App\Repositories;

interface MemberGroupRepositoryInterface extends BaseRepositoryInterface {

    public function getForSelect();

    public function getLocationsForSelect();

    public function canBeDeleted($id);
}