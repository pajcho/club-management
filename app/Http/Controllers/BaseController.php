<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;

class BaseController extends Controller
{
    public function __construct()
    {
        View::share('activeMenu', false);
    }
}
