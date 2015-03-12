<?php namespace App\Modules\Users\Controllers;

use App\Http\Controllers\AdminController;
use App\Modules\Members\Repositories\MemberGroupRepositoryInterface;
use App\Modules\Users\Internal\Validators\UserValidator;
use App\Modules\Users\Repositories\UserRepositoryInterface;
use App\Services\Theme;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class UserController extends AdminController
{

    private $users;
    private $groups;

    public function __construct(UserRepositoryInterface $users, MemberGroupRepositoryInterface $groups)
    {
        parent::__construct();

        $this->filterRequests();

        View::share('activeMenu', 'users');

        $this->users  = $users;
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
        $users = $this->users->filterWith(['groups'], $input);

        $groups = ['' => 'Group'] + $this->groups->getForSelect();

        return view(Theme::view('user.index'))->with(compact('users', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $groups = $this->groups->getForSelect();
        $types  = $this->users->getTypesForSelect();

        return view(Theme::view('user.create'))->with(compact('groups', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $validator = new UserValidator();

        if ($validator->validate(Input::all(), 'create')) {
            // validation passed
            $this->users->create($validator->data());

            // Create redirect depending on submit button
            $redirect = redirect(route('user.index'));

            if (Input::get('create_and_add', false))
                $redirect = redirect(route('user.create'));

            return $redirect->withSuccess('User created!');
        }

        // validation failed
        return redirect(route('user.create'))->withInput()->withErrors($validator->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->users->find($id);

        if (!$user) app()->abort(404);

        $groups = $this->groups->getForSelect();
        $types  = $this->users->getTypesForSelect();

        return view(Theme::view('user.update'))->with(compact('user', 'groups', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function update($id)
    {
        $validator = new UserValidator();

        if ($validator->validate(Input::all(), 'update', $id)) {
            // validation passed
            $this->users->update($id, $validator->data());

            return redirect(route('user.show', $id))->withSuccess('Details updated!');
        }

        // validation failed
        return redirect(route('user.show', $id))->withInput()->withErrors($validator->errors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->users->delete($id);

        return back()->withInput()->withSuccess('User deleted!');
    }

    /**
     * Filter user requests, because some actions
     * are only allowed to admin users
     */
    private function filterRequests()
    {
        if (Route::input('user')) {
            // Allow only admin users and owners to edit their profile
            $this->middleware('allowOnlyAdminOrCurrentUser', ['only' => ['show', 'edit', 'update']]);
        }

        // Allow only admin users to do requests here
        $this->middleware('allowOnlyAdmin', ['except' => ['show', 'edit', 'update']]);
    }

}
