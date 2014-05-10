<?php namespace App\Controllers;

use App\Internal\Validators\ResultValidator;
use App\Repositories\MemberRepositoryInterface;
use App\Repositories\ResultRepositoryInterface;
use App\Repositories\ResultCategoryRepositoryInterface;
use App\Service\Theme;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class ResultController extends AdminController {

    private $results;
    private $categories;
    private $members;

    public function __construct(ResultRepositoryInterface $results, ResultCategoryRepositoryInterface $categories, MemberRepositoryInterface $members)
    {
        parent::__construct();

        View::share('activeMenu', 'results');

        $this->results = $results;
        $this->categories = $categories;
        $this->members = $members;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $input = Input::get();

        // Get all results
        $results = $this->results->filter($input);

        // Get variables for selectbox
        extract($this->generateVariables());

        // Generate filters title
        $filters_title = '';
        if(isset($categories[Input::get('category_id') ?: false])) $filters_title = $categories[Input::get('category_id')] . ' / ' . $filters_title;
        if(isset($years[Input::get('year') ?: false])) $filters_title = $years[Input::get('year')] . ' / ' . $filters_title;
        if(isset($types[Input::get('type') ?: false])) $filters_title = $types[Input::get('type')] . ' / ' . $filters_title;
        if(Input::get('name') ?: false) $filters_title = Input::get('name') . ' / ' . $filters_title;

        // Default filters title
        $filters_title = trim($filters_title, ' / ');
        $filters_title = $filters_title ?: 'Filter results';

        return View::make(Theme::view('result.index'))->with(compact('results', 'filters_title', 'categories', 'years', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Get variables for selectbox
        extract($this->generateVariables());

        return View::make(Theme::view('result.create'))->with(compact('categories', 'members', 'years', 'places', 'types'));
    }

    protected function generateVariables()
    {
        $data['categories'] = $this->categories->getForSelect(true);
        $data['members'] = $this->members->getForSelect();

        // Generate years
        $years = range(date('Y'), 2000);
        $data['years'] = array_combine($years, $years);
        $data['years'] = array('' => 'Year') + $data['years'];

        // Generate places
        $places = range(1, 50);
        $data['places'] = array_combine($places, $places);
        $data['places'] = array('' => 'Place') + $data['places'];

        // Generate types
        $types = array('pojedinacno', 'grupno', 'po spravi');
        $data['types'] = array_combine($types, $types);
        $data['types'] = array('' => 'Type') + $data['types'];

        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $validator = new ResultValidator();

        if ($validator->validate(Input::all(), 'create'))
        {
            // validation passed
            $this->results->create($validator->data());

            // Create redirect depending on submit button
            $redirect = Redirect::route('result.index');

            if(Input::get('create_and_add', false))
                $redirect = Redirect::route('result.create');


            return $redirect->withSuccess('Result created!');
        }

        // validation failed
        return Redirect::route('result.create')->withInput()->withErrors($validator->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $result = $this->results->find($id);

        if(!$result) App::abort(404);

        // Get variables for selectbox
        extract($this->generateVariables());

        return View::make(Theme::view('result.update'))->with(compact('result', 'categories', 'members', 'years', 'places', 'types'));
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
        $validator = new ResultValidator();

        if ($validator->validate(Input::all(), 'update', $id))
        {
            // validation passed
            $this->results->update($id, $validator->data());

            return Redirect::route('result.show', $id)->withSuccess('Details updated!');
        }

        // validation failed
        return Redirect::route('result.show', $id)->withInput()->withErrors($validator->errors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->results->delete($id);

        return Redirect::back()->withInput()->withSuccess('Result deleted!');
    }

}
