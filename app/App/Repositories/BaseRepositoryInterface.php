<?php namespace App\Repositories;

interface BaseRepositoryInterface
{
	public function all();

	public function allWith(array $with);

	public function create($input);

	public function update($id, $input);

	public function find($id);

	public function findWith($id, array $with);

	public function delete($id);

    public function filter(array $params);
}