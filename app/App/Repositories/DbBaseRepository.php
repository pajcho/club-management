<?php namespace App\Repositories;

abstract class DbBaseRepository extends BaseRepository {

	protected $model;
	protected $orderBy = 'id';
	protected $orderDirection = 'asc';
	protected $perPage = 15;

	public function __construct($model)
	{
		$this->model = $model;
	}

	public function all()
	{
		return $this->model->all();
	}

	public function allWith(array $with)
	{
		return $this->model->with($with)->get();
	}

	public function create($input)
	{
		return $this->model->create($input);
	}

	public function update($id, $input)
	{
		return $this->model->find($id)->update($input);
	}

	public function find($id)
	{
		$model = $this->model->find($id);

		return $model ?: null;
	}

	public function findWith($id, array $with)
	{
		return $this->model->with($with)->find($id);
	}

	public function delete($id)
	{
		return $this->model->find($id)->delete();
	}

    public function filter(array $params, $paginate = true)
    {
        // Default filter by every database column
        foreach($this->model->getColumnNames() as $column)
        {
            if(isset($params[$column]) && ($params[$column] === '0' || !empty($params[$column])))
            {
                $this->model = $this->model->where($column, '=', $params[$column]);
            }
        }

        $this->model = $this->model->orderBy($this->orderBy, $this->orderDirection);

        return $paginate ? $this->model->paginate($this->perPage) : $this->model->get();
    }
}