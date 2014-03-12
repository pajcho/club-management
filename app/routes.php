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
    Route::get('{group}/attendance', array('as' => 'group.attendance', 'uses' => 'App\Controllers\MemberGroupController@attendance'));
    Route::get('{group}/payments', array('as' => 'group.payments', 'uses' => 'App\Controllers\MemberGroupController@payments'));

    // Group members
    Route::get('{group}/member', array('as' => 'group.member.index', 'uses' => 'App\Controllers\MemberController@index'));
});

Route::group(array('prefix' => 'settings'), function()
{
    Route::get('/', array('as' => 'settings.index', 'uses' => 'App\Controllers\SettingsController@index'));
    Route::post('/', array('as' => 'settings.store', 'uses' => 'App\Controllers\SettingsController@store'));
});

Route::get('/', function()
{
	return Redirect::route('member.index');
});