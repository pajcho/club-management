<?php namespace Api\Modules\Auth\Controllers;

use Api\Controllers\BaseController;
use App\Service\Theme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class AuthController extends BaseController {

    /**
     * Display login form
     *
     * @return Response
     */
	public function login()
	{
        return View::make(Theme::view('auth.login'));
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

        return Redirect::route('login')->withError('Invalid credentials!');
	}

	/**
	 * Logout user
	 *
	 * @return Response
	 */
	public function logout()
	{
        Auth::logout();

        return Redirect::route('login')->withSuccess('Thank you, come again!');
	}
}
