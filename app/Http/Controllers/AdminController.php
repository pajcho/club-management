<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AdminController extends BaseController {

    /**
     * Currently logged in user
     *
     * @var
     */
    public $currentUser;

    public function __construct()
    {
        parent::__construct();

        // Set current user
        $this->currentUser = Auth::user();
        View::share('currentUser', $this->currentUser);
    }
}
