<?php namespace App\Modules\Users\Repositories;

use App\Modules\Users\Models\User;
use App\Repositories\DbBaseRepository;

class DbUserRepository extends DbBaseRepository implements UserRepositoryInterface {

    protected $model;
    protected $columnNames;
    protected $orderBy = array('first_name' => 'asc', 'last_name' => 'asc');
    protected $perPage = 15;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * We need to attach groups when creating user
     *
     * @param $input
     * @return mixed
     */
    public function create($input)
    {
        $user = $this->model->create($input);

        // Sync user groups
        $this->syncGroups($user, $input);

        return $user;
    }

    /**
     * We need to attach groups when updating user
     *
     * @param $id
     * @param $input
     * @return mixed
     */
    public function update($id, $input)
    {
        $this->preReturnFilters();

        $user = $this->model->find($id);

        // Sync user groups
        $this->syncGroups($user, $input);

        return $user->update($input);
    }

    /**
     * Sync user groups
     *
     * @param $user
     * @param $input
     */
    protected function syncGroups($user, $input)
    {
        // Sync user groups
        if($groups = array_get($input, 'groups', array()))
        {
            $user->groups()->sync($groups);
        }
    }

    /**
     * Get all users as array to use for select box
     */
    public function getForSelect()
    {
        return $this->all()->lists('full_name', 'id')->all();
    }

    /**
     * Get all user types as array for select box
     *
     * @return array
     */
    function getTypesForSelect()
    {
        return array(
            'trainer' => 'trainer',
            'admin' => 'admin'
        );
    }
}