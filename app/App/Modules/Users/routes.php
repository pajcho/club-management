<?php

    $controller = 'App\Modules\Users\Controllers\UserController';
    $attendanceController = 'App\Modules\Users\Controllers\UserAttendanceController';

    /**
     * We are using this instead of resource routes just
     * to be able to have route names preserved
     * if there is future change in names
     */
    Route::group(array('prefix' => 'user', 'before' => 'auth'), function() use ($controller, $attendanceController)
    {
        Route::get('/', array('as' => 'user.index', 'uses' => $controller . '@index'));
        Route::get('create', array('as' => 'user.create', 'uses' => $controller . '@create'));
        Route::post('/', array('as' => 'user.store', 'uses' => $controller . '@store'));
        Route::get('{user}', array('as' => 'user.show', 'uses' => $controller . '@show'));
        Route::get('{user}/edit', array('as' => 'user.edit', 'uses' => $controller . '@edit'));
        Route::put('{user}', array('as' => 'user.update', 'uses' => $controller . '@update'));
        Route::delete('{user}', array('as' => 'user.destroy', 'uses' => $controller . '@destroy'));

        Route::get('{user}/attendance', array('as' => 'user.attendance.index', 'uses' => $attendanceController . '@index'));
        Route::put('{user}/attendance', array('as' => 'user.attendance.update', 'uses' => $attendanceController . '@update'))
            ->where(array('user' => '[0-9]+'));
    });