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
    /**
     * We are using this instead of resource routes just
     * to be able to have route names preserved
     * if there is future change in names
     */
    Route::group(array('prefix' => 'member'), function()
    {
        Route::get('/', array('as' => 'member.index', 'uses' => 'App\Controllers\MemberController@index'));
        Route::get('create', array('as' => 'member.create', 'uses' => 'App\Controllers\MemberController@create'));
        Route::post('/', array('as' => 'member.store', 'uses' => 'App\Controllers\MemberController@store'));
        Route::get('{member}', array('as' => 'member.show', 'uses' => 'App\Controllers\MemberController@show'));
        Route::get('{member}/edit', array('as' => 'member.edit', 'uses' => 'App\Controllers\MemberController@edit'));
        Route::put('{member}', array('as' => 'member.update', 'uses' => 'App\Controllers\MemberController@update'));
        Route::delete('{member}', array('as' => 'member.destroy', 'uses' => 'App\Controllers\MemberController@destroy'));
    });

    Route::group(array('prefix' => 'user'), function()
    {
        Route::get('/', array('as' => 'user.index', 'uses' => 'App\Controllers\UserController@index'));
        Route::get('create', array('as' => 'user.create', 'uses' => 'App\Controllers\UserController@create'));
        Route::post('/', array('as' => 'user.store', 'uses' => 'App\Controllers\UserController@store'));
        Route::get('{user}', array('as' => 'user.show', 'uses' => 'App\Controllers\UserController@show'));
        Route::get('{user}/edit', array('as' => 'user.edit', 'uses' => 'App\Controllers\UserController@edit'));
        Route::put('{user}', array('as' => 'user.update', 'uses' => 'App\Controllers\UserController@update'));
        Route::delete('{user}', array('as' => 'user.destroy', 'uses' => 'App\Controllers\UserController@destroy'));
    });

    Route::group(array('prefix' => 'group'), function()
    {
        Route::get('/', array('as' => 'group.index', 'uses' => 'App\Controllers\MemberGroupController@index'));
        Route::get('create', array('as' => 'group.create', 'uses' => 'App\Controllers\MemberGroupController@create'));
        Route::post('/', array('as' => 'group.store', 'uses' => 'App\Controllers\MemberGroupController@store'));
        Route::get('{group}', array('as' => 'group.show', 'uses' => 'App\Controllers\MemberGroupController@show'));
        Route::get('{group}/edit', array('as' => 'group.edit', 'uses' => 'App\Controllers\MemberGroupController@edit'));
        Route::put('{group}', array('as' => 'group.update', 'uses' => 'App\Controllers\MemberGroupController@update'));
        Route::delete('{group}', array('as' => 'group.destroy', 'uses' => 'App\Controllers\MemberGroupController@destroy'));

        // Group PDF documents
        Route::get('{group}/attendance/{year}/{month}', array('as' => 'group.attendance', 'uses' => 'App\Controllers\MemberGroupController@attendance'))
            ->where(array('year', '[0-9]+', 'month', '[0-9]+'));
        Route::get('{group}/payments/{year}/{month}', array('as' => 'group.payments', 'uses' => 'App\Controllers\MemberGroupController@payments'))
            ->where(array('year', '[0-9]+', 'month', '[0-9]+'));

        // Group details (Payments & Attendance)
        Route::group(array('prefix' => '{group}/details'), function()
        {
            Route::get('/', array('as' => 'group.details.index', 'uses' => 'App\Controllers\MemberGroupDetailsController@index'));
            Route::get('{year}/{month}', array('as' => 'group.details.show', 'uses' => 'App\Controllers\MemberGroupDetailsController@show'))
                ->where(array('year', '[0-9]+', 'month', '[0-9]+'));
            Route::put('{year}/{month}', array('as' => 'group.details.update', 'uses' => 'App\Controllers\MemberGroupDetailsController@update'))
                ->where(array('year', '[0-9]+', 'month', '[0-9]+'));
        });
    });

    Route::group(array('prefix' => 'settings'), function()
    {
        Route::get('/', array('as' => 'settings.index', 'uses' => 'App\Controllers\SettingsController@index'));
        Route::post('/', array('as' => 'settings.store', 'uses' => 'App\Controllers\SettingsController@store'));
    });

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