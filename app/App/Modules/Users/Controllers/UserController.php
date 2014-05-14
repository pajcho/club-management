<?php namespace App\Modules\Users\Controllers;

use App\Controllers\AdminController;
use App\Modules\Members\Repositories\MemberGroupRepositoryInterface;
use App\Modules\Users\Internal\Validators\UserValidator;
use App\Modules\Users\Repositories\UserRepositoryInterface;
use App\Service\Theme;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class UserController extends AdminController {

    private $users;
    private $groups;

    public function __construct(UserRepositoryInterface $users, MemberGroupRepositoryInterface $groups)
    {
        parent::__construct();

        View::share('activeMenu', 'users');

        $this->users = $users;
        $this->groups = $groups;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $input = Input::get();

        // Get all users
        $users = $this->users->filterWith(array('groups'), $input);

        $groups = array('' => 'Group') + $this->groups->getForSelect();

        return View::make(Theme::view('user.index'))->with(compact('users', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $groups = $this->groups->getForSelect();
        $types = $this->users->getTypesForSelect();

        return View::make(Theme::view('user.create'))->with(compact('groups', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $validator = new UserValidator();

        if ($validator->validate(Input::all(), 'create'))
        {
            // validation passed
            $this->users->create($validator->data());

            // Create redirect depending on submit button
            $redirect = Redirect::route('user.index');

            if(Input::get('create_and_add', false))
                $redirect = Redirect::route('user.create');


            return $redirect->withSuccess('User created!');
        }

        // validation failed
        return Redirect::route('user.create')->withInput()->withErrors($validator->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = $this->users->find($id);

        if(!$user) App::abort(404);

        $groups = $this->groups->getForSelect();
        $types = $this->users->getTypesForSelect();

        return View::make(Theme::view('user.update'))->with(compact('user', 'groups', 'types'));
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
        $validator = new UserValidator();

        if ($validator->validate(Input::all(), 'update', $id))
        {
            // validation passed
            $this->users->update($id, $validator->data());

            return Redirect::route('user.show', $id)->withSuccess('Details updated!');
        }

        // validation failed
        return Redirect::route('user.show', $id)->withInput()->withErrors($validator->errors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->users->delete($id);

        return Redirect::back()->withInput()->withSuccess('User deleted!');
    }

}
