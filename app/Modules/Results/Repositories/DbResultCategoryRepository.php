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

    public function filter(array $params = array(), $paginate = true)
    {
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

        return $paginate ? $this->model->paginate($this->perPage) : $this->model->get();
    }

    /**
     * Get all result categories as array to use for select box
     */
    public function getForSelect($includeEmpty = true)
    {
        $result = $this->all()->lists('name', 'id');

        if($includeEmpty) $result = array('' => 'Category') + $result;

        return $result;
    }
}