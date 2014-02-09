<?php namespace App\Controllers;

use App\Internal\Validators\MemberValidator;
use App\Repositories\Member\MemberRepositoryInterface;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class MemberController extends BaseController {

    private $members;
    
	public function __construct(MemberRepositoryInterface $members)
	{
		parent::__construct();
        
        $this->members = $members;
	}

    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Get all members
        $members = $this->members->getAll();
        
        return View::make('member.index')->withMembers($members);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('member.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $validator = new MemberValidator();

        if ($validator->validate(Input::all(), 'create'))
        {
            // validation passed
            $this->members->create($validator->data());

            return Redirect::route('member.index')->withSuccess('Member created!');
        }

        // validation failed
		return Redirect::route('member.create')->withInput()->withErrors($validator->errors());
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
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
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
    
}
