<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class AllowOnlyAdminOrCurrentUser {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard $auth
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if(!$this->auth->check() || (!$this->auth->user()->isAdmin() && $this->auth->user()->id != $request->route('user')))
		{
			return redirect()->to('/')->withError('You don\'t have permission to do this!');
		}

		return $next($request);
	}

}
