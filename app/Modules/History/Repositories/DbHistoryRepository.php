<?php namespace App\Modules\History\Repositories;

use App\Modules\History\Models\History;
use App\Repositories\DbBaseRepository;

class DbHistoryRepository extends DbBaseRepository implements HistoryRepositoryInterface {

    protected $model;
    protected $columnNames;
    protected $orderBy = array('created_at' => 'desc');
    protected $perPage = 15;

    public function __construct(History $model)
    {
        parent::__construct($model);
    }

    public function filter(array $params = array(), $paginate = true)
    {
        // Filter by name
        if(isset($params['message']) && !empty($params['message']))
        {
            $this->model = $this->model->where(function($query) use ($params){
                $query->where('message', 'LIKE', '%' . $params['message'] . '%');
            });
        }

        // Order by
        foreach($this->orderBy as $orderBy => $orderDirection)
            $this->model = $this->model->orderBy($orderBy, $orderDirection);

        $this->preReturnFilters();

        return $paginate ? $this->model->paginate($this->perPage) : $this->model->get();
    }
}