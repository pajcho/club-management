<?php namespace App\Controllers;

use App\Internal\Validators\MemberGroupValidator;
use App\Repositories\MemberGroup\MemberGroupRepositoryInterface;
use App\Service\Theme;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class MemberGroupController extends BaseController {

    private $memberGroups;
    
	public function __construct(MemberGroupRepositoryInterface $memberGroups)
	{
		parent::__construct();

        View::share('activeMenu', 'groups');

        $this->memberGroups = $memberGroups;
	}

    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Get all members
        $memberGroups = $this->memberGroups->filter(Input::get());
        
        return View::make(Theme::view('group.index'))->with('memberGroups', $memberGroups);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make(Theme::view('group.create'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $validator = new MemberGroupValidator();

        if ($validator->validate(Input::all(), 'create'))
        {
            // validation passed
            $this->memberGroups->create($this->prepareTimesForInsert($validator->data()));

            // Create redirect depending on submit button
            $redirect = Redirect::route('group.index');

            if(Input::get('create_and_add', false))
                $redirect = Redirect::route('group.create')->withInput();


            return $redirect->withSuccess('Member group created!');
        }

        // validation failed
		return Redirect::route('group.create')->withInput()->withErrors($validator->errors());
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $memberGroup = $this->memberGroups->getById($id);

        return View::make(Theme::view('group.update'))->with('memberGroup', $memberGroup);
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

        $memberGroup = $this->memberGroups->getById($id);

        $validator = new MemberGroupValidator();

        if ($validator->validate(Input::all(), 'update', $memberGroup->id))
        {
            // validation passed
            $this->memberGroups->update($memberGroup, $this->prepareTimesForInsert($validator->data()));

            return Redirect::route('group.show', $memberGroup->id)->withSuccess('Details updated!');
        }

        // validation failed
        return Redirect::route('group.show', $memberGroup->id)->withInput()->withErrors($validator->errors());
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $memberGroup = $this->memberGroups->getById($id);

        // We can not delete group that has members
        if(count($memberGroup->members))
        {
            return Redirect::back()->withInput()->withError('Member group already has members! In order to delete this group first remove all members from it.');
        }

        $this->memberGroups->delete($memberGroup);

        return Redirect::back()->withInput()->withSuccess('Member group deleted!');
	}

    /**
     * Prepare array columns for database by converting them to JSON
     *
     * @param $data
     */
    private function prepareTimesForInsert($data)
    {
        foreach($data as $key => $value)
        {
            if(is_array($value)) $data[$key] = json_encode($value);
        }

        return $data;
    }
    
}
