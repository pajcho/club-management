<?php namespace App\Repositories;

use App\Models\Member;

class DbMemberRepository extends DbBaseRepository implements MemberRepositoryInterface {

    protected $model;
    protected $orderBy = 'dob';
    protected $orderDirection = 'asc';
    protected $perPage = 15;

    public function __construct(Member $model)
    {
        parent::__construct($model);
    }

    public function filter(array $params = array(), $paginate = true)
    {
        // Default filter by every database column
        foreach($this->model->getColumnNames() as $column)
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
                    $this->model = $this->model->where(function($query) use ($names){
                        $query->where('first_name', 'LIKE', '%' . $names[0] . '%');
                        $query->orWhere('last_name', 'LIKE', '%' . $names[0] . '%');
                    });
                    break;
                case 2:
                    $this->model = $this->model->where(function($query) use ($names){
                        $query->where('first_name', 'LIKE', '%' . $names[0] . '%');
                        $query->where('last_name', 'LIKE', '%' . $names[1] . '%');
                    })->orWhere(function($query) use ($names){
                        $query->where('last_name', 'LIKE', '%' . $names[0] . '%');
                        $query->where('first_name', 'LIKE', '%' . $names[1] . '%');
                    });
                    break;
            }
        }

        // Filter by location
        if(isset($params['location']) && !empty($params['location']))
        {
            $this->model = $this->model->whereHas('group', function($query) use ($params){
                $query->where('location', '=', $params['location']);
            });
        }

        $this->model = $this->model->orderBy($this->orderBy, $this->orderDirection);

        return $paginate ? $this->model->paginate($this->perPage) : $this->model->get();
    }
}