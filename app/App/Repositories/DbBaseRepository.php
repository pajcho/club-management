<?php namespace App\Repositories;

abstract class DbBaseRepository extends BaseRepository {

    protected $model;
    protected $columnNames;
    protected $orderBy = array('id' => 'asc');
    protected $perPage = 15;
    protected $allowEmbeds = array();

    public function __construct($model)
    {
        $this->model = $model;
        $this->columnNames = $this->model->getColumnNames();
    }

    public function all()
    {
        // Order by
        foreach($this->orderBy as $orderBy => $orderDirection)
            $this->model = $this->model->orderBy($orderBy, $orderDirection);

        $this->preReturnFilters();

        return $this->model->get();
    }

    public function allWith(array $with)
    {
        if(!empty($with))
        {
            $this->model = $this->model->with(array_intersect($this->allowEmbeds, $with));
        }

        return $this->all();
    }

    public function create($input)
    {
        return $this->model->create($input);
    }

    public function update($id, $input)
    {
        $this->preReturnFilters();

        $item = $this->model->find($id);

        $item->update($input);
        return $item;
    }

    public function find($id)
    {
        $this->preReturnFilters();

        $model = $this->model->find($id);

        return $model ?: null;
    }

    public function findWith($id, array $with)
    {
        if(!empty($with))
        {
            $this->model = $this->model->with(array_intersect($this->allowEmbeds, $with));
        }

        return $this->find($id);
    }

    public function delete($id)
    {
        $this->preReturnFilters();

        // Get the item
        $item = $this->model->find($id);

        // Call pre delete actions
        $this->preDelete($item);

        return $item->delete();
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

        // Order by
        foreach($this->orderBy as $orderBy => $orderDirection)
            $this->model = $this->model->orderBy($orderBy, $orderDirection);

        $this->preReturnFilters();

        return $paginate ? $this->model->paginate($this->perPage) : $this->model->get();
    }

    public function filterWith(array $with, array $params, $paginate = true)
    {
        if(!empty($with))
        {
            $this->model = $this->model->with(array_intersect($this->allowEmbeds, $with));
        }

        return $this->filter($params, $paginate);
    }

    public function preReturnFilters()
    {
        // Define model filters here to call before queriing
    }

    public function preDelete($item)
    {
        // Define actions to be called pre deleting
    }
}