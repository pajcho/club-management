<?php namespace App\Modules\Results\Controllers;

use App\Http\Controllers\AdminController;
use App\Modules\Results\Internal\Validators\ResultCategoryValidator;
use App\Modules\Results\Repositories\ResultCategoryRepositoryInterface;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class ResultCategoryController extends AdminController {

    private $resultCategories;

	public function __construct(ResultCategoryRepositoryInterface $resultCategories)
	{
		parent::__construct();

        View::share('activeMenu', 'result.categories');

        $this->resultCategories = $resultCategories;
	}

    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Get all result categories
        $resultCategories = $this->resultCategories->filter(Input::get());

        // Generate filters title
        $filters_title = 'Filter result categories';
        if(Input::get('name') ?: false) $filters_title = Input::get('name');
        
        return view('result.category.index')->with(compact('resultCategories', 'filters_title'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $categories = $this->resultCategories->getForSelect(true);

		return view('result.category.create')->with(compact('categories'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $validator = new ResultCategoryValidator();

        if ($validator->validate(Input::all(), 'create'))
        {
            // validation passed
            $this->resultCategories->create($validator->data());

            // Create redirect depending on submit button
            $redirect = redirect(route('result.category.index'));

            if(Input::get('create_and_add', false))
                $redirect = redirect(route('result.category.create'));


            return $redirect->withSuccess('Result category created!');
        }

        // validation failed
		return redirect(route('result.category.create'))->withInput()->withErrors($validator->errors());
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $resultCategory = $this->resultCategories->find($id);
        $categories = $this->resultCategories->getForSelect(true);

        return view('result.category.update')->with(compact('resultCategory', 'categories'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        //
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $validator = new ResultCategoryValidator();

        if ($validator->validate(Input::all(), 'update', $id))
        {
            // validation passed
            $this->resultCategories->update($id, $validator->data());

            return redirect(route('result.category.show', $id))->withSuccess('Details updated!');
        }

        // validation failed
        return redirect(route('result.category.show', $id))->withInput()->withErrors($validator->errors());
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        // We can not delete result group if there are results linked to it
        if(!$this->resultCategories->canBeDeleted($id))
        {
            return back()->withInput()->withError('Result category has results linked to it! In order to delete this group first unlink all results.');
        }

        $this->resultCategories->delete($id);

        return back()->withInput()->withSuccess('Result category deleted!');
	}
}
