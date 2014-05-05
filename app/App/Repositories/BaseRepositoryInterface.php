<?php namespace App\Repositories;

interface BaseRepositoryInterface
{
	function all();

	function allWith(array $with);

	function create($input);

	function update($id, $input);

	function find($id);

	function findWith($id, array $with);

	function delete($id);

    function filter(array $params, $paginate = true);

    function filterWith(array $with, array $params, $paginate = true);
}