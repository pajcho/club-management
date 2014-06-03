<?php namespace App\Modules\Members\Controllers;

use App\Controllers\AdminController;
use App\Service\Theme;
use Dingo\Api\Dispatcher;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class MemberController extends AdminController {

    /**
     * @var \Dingo\Api\Dispatcher
     */
    private $api;

    public function __construct(Dispatcher $api)
    {
        parent::__construct();

        View::share('activeMenu', 'members');

        $this->api = $api;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $input = Input::get();

        $members = $this->api->with($input)->get('members');
        $filters = $this->api->with($input)->get('members/filters');

        return View::make(Theme::view('member.index'))->with(compact('members') + $filters);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $input = Input::get();

        $filters = $this->api->with($input)->get('members/filters');
        $groups = $filters['groups'];

        return View::make(Theme::view('member.create'))->with(compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        try
        {
            $this->api->with(Input::get())->post('members');
        }
        catch(StoreResourceFailedException $e)
        {
            // validation failed
            return Redirect::route('member.create')->withInput()->withErrors($e->getErrors());
        }

        // Create redirect depending on submit button
        $redirect = Redirect::route('member.index');

        if(Input::get('create_and_add', false))
            $redirect = Redirect::route('member.create');

        return $redirect->withSuccess('Member created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $input = Input::get();

        $member = $this->api->get('members/' . $id);
        $filters = $this->api->with($input)->get('members/filters');
        $groups = $filters['groups'];

        return View::make(Theme::view('member.update'))->with(compact('member', 'groups'));
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
        try
        {
            $this->api->with(Input::get())->put('members/' . $id);
        }
        catch(UpdateResourceFailedException $e)
        {
            // validation failed
            return Redirect::route('member.show', $id)->withInput()->withErrors($e->getErrors());
        }

        return Redirect::route('member.show', $id)->withSuccess('Details updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->api->delete('members/' . $id);

        return Redirect::back()->withInput()->withSuccess('Member deleted!');
    }

}
