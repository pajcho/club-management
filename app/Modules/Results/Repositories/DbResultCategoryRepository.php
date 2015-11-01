<?php namespace App\Modules\Results\Repositories;

use App\Modules\Results\Models\ResultCategory;
use App\Repositories\DbBaseRepository;

class DbResultCategoryRepository extends DbBaseRepository implements ResultCategoryRepositoryInterface {

    protected $model;
    protected $columnNames;
    protected $orderBy = array('name' => 'asc');
    protected $perPage = 15;

    public function __construct(ResultCategory $model)
    {
        parent::__construct($model);
    }

    public function filter(array $params = [], $paginate = true)
    {
        $this->paginate = !!$paginate;

        // Filter by name
        if(isset($params['name']) && !empty($params['name']))
        {
            $this->model = $this->model->where(function($query) use ($params){
                $query->where('name', 'LIKE', '%' . $params['name'] . '%');
            });
        }

        // Order by
        foreach($this->orderBy as $orderBy => $orderDirection)
            $this->model = $this->model->orderBy($orderBy, $orderDirection);

        $this->preReturnFilters();

        return $this->paginate ? $this->model->paginate($this->perPage) : $this->model->get();
    }

    /**
     * Get all result categories as array to use for select box
     *
     * @param bool $includeEmpty
     *
     * @return array
     */
    public function getForSelect($includeEmpty = true)
    {
        $result = $this->all()->lists('name', 'id')->all();

        if($includeEmpty) $result = array('' => 'Category') + $result;

        return $result;
    }

    /**
     * Check to see if result category can be deleted
     *
     * @param $id
     *
     * @return bool
     */
    public function canBeDeleted($id)
    {
        return $this->model->find($id)->results()->count() ? false : true;
    }
}