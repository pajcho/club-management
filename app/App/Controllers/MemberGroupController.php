<?php namespace App\Controllers;

use App\Internal\Validators\MemberGroupValidator;
use App\Repositories\MemberGroupRepositoryInterface;
use App\Service\Theme;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Webpatser\Sanitize\Sanitize;

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
        $memberGroup = $this->memberGroups->find($id);

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
        $validator = new MemberGroupValidator();

        if ($validator->validate(Input::all(), 'update', $id))
        {
            // validation passed
            $this->memberGroups->update($id, $this->prepareTimesForInsert($validator->data()));

            return Redirect::route('group.show', $id)->withSuccess('Details updated!');
        }

        // validation failed
        return Redirect::route('group.show', $id)->withInput()->withErrors($validator->errors());
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        // We can not delete group that has members
        if(!$this->memberGroups->canBeDeleted($id))
        {
            return Redirect::back()->withInput()->withError('Member group already has members! In order to delete this group first remove all members from it.');
        }

        $this->memberGroups->delete($id);

        return Redirect::back()->withInput()->withSuccess('Member group deleted!');
	}

    /**
     * Generate attendance PDF document
     *
     * @param  int  $id
     * @return Response
     */
    public function attendance($id)
    {
        $pdf = App::make('App\Service\Pdf\PhantomPdf');
        $membersRepo = App::make('App\Repositories\MemberRepositoryInterface');
        $memberGroup = $this->memberGroups->find($id);

        // Get all active group members
        $members = $membersRepo->filter(array(
            'group_id'  => $memberGroup->id,
            'active'    => 1
        ), false);

        $view = View::make(Theme::view('group.attendance'))->with(compact('memberGroup', 'members'))->render();
        $documentName = Sanitize::string($memberGroup->name . ' ' . Config::get('settings.att_doc_title'));

        return $pdf->download($view, $documentName);
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
