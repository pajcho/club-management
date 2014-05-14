<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(array('prefix' => '', 'before' => 'auth'), function()
{
    Route::get('/', function()
    {
        Session::reflash();
        return Redirect::route('member.index');
    });
});

Route::group(array('prefix' => 'auth'), function()
{
    Route::get('login', array('as' => 'login', 'uses' => 'App\Controllers\AuthController@login'));
    Route::post('login', array('as' => 'login', 'uses' => 'App\Controllers\AuthController@loginPost'));

    Route::get('logout', array('as' => 'logout', 'uses' => 'App\Controllers\AuthController@logout'));
});