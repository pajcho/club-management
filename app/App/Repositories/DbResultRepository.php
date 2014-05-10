<?php namespace App\Repositories;

use App\Models\Result;

class DbResultRepository extends DbBaseRepository implements ResultRepositoryInterface {

    protected $model;
    protected $columnNames;
    protected $orderBy = array('id' => 'desc');
    protected $perPage = 15;

    public function __construct(Result $model)
    {
        parent::__construct($model);
    }

    public function filter(array $params, $paginate = true)
    {
        // Default filter by every database column
        foreach($this->columnNames as $column)
        {
            if(isset($params[$column]) && ($params[$column] === '0' || !empty($params[$column])))
            {
                $this->model = $this->model->where($column, '=', $params[$column]);
            }
        }

        // Set class properties
        foreach($params as $key => $param)
        {
            if(isset($this->{$key})) $this->{$key} = $param;
        }

        // Filter by name
        if(isset($params['name']) && !empty($params['name']))
        {
            $names = explode(' ', $params['name'], 2);

            switch(count($names))
            {
                case 1:
                    $this->model = $this->model->whereHas('member', function($query) use ($names){
                        $query->where(function($query) use ($names){
                            $query->where('first_name', 'LIKE', '%' . $names[0] . '%');
                            $query->orWhere('last_name', 'LIKE', '%' . $names[0] . '%');
                        });
                    });
                    break;
                case 2:
                    $this->model = $this->model->whereHas('member', function($query) use ($names){
                        $query->where(function($query) use ($names){
                            $query->where(function($query) use ($names){
                                $query->where('first_name', 'LIKE', '%' . $names[0] . '%');
                                $query->where('last_name', 'LIKE', '%' . $names[1] . '%');
                            })->orWhere(function($query) use ($names){
                                $query->where('last_name', 'LIKE', '%' . $names[0] . '%');
                                $query->where('first_name', 'LIKE', '%' . $names[1] . '%');
                            });
                        });
                    });
                    break;
            }
        }

        // Order by
        foreach($this->orderBy as $orderBy => $orderDirection)
            $this->model = $this->model->orderBy($orderBy, $orderDirection);

        $this->preReturnFilters();

        return $paginate ? $this->model->paginate($this->perPage) : $this->model->get();
    }
}