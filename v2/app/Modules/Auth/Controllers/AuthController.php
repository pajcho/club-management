<?php namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\BaseController;
use App\Services\Theme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class AuthController extends BaseController {

    public function __construct()
	{
		parent::__construct();
    }

    /**
     * Display login form
     *
     * @return Response
     */
	public function login()
	{
        return view(Theme::view('auth.login'));
	}

	/**
	 * Process login form
	 *
	 * @return Response
	 */
	public function loginPost()
	{
        if (Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password')), Input::get('remember', 0) == 1))
        {
            return Redirect::intended('/')->withSuccess('Logged in successfully!');
        }

        return redirect(route('login'))->withError('Invalid credentials!');
	}

	/**
	 * Logout user
	 *
	 * @return Response
	 */
	public function logout()
	{
        Auth::logout();

        return redirect(route('login'))->withSuccess('Thank you, come again!');
	}
}
