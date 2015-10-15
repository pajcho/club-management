<?php

    $controller = 'App\Modules\Users\Controllers\UserController';
    $attendanceController = 'App\Modules\Users\Controllers\UserAttendanceController';

    /**
     * We are using this instead of resource routes just
     * to be able to have route names preserved
     * if there is future change in names
     */
    Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () use ($controller, $attendanceController) {
        get('/', ['as' => 'user.index', 'uses' => $controller . '@index']);
        get('create', ['as' => 'user.create', 'uses' => $controller . '@create']);
        post('/', ['as' => 'user.store', 'uses' => $controller . '@store']);
        get('{user}', ['as' => 'user.show', 'uses' => $controller . '@show'])->where(['user' => '[0-9]+']);
        get('{user}/edit', ['as' => 'user.edit', 'uses' => $controller . '@edit'])->where(['user' => '[0-9]+']);
        put('{user}', ['as' => 'user.update', 'uses' => $controller . '@update'])->where(['user' => '[0-9]+']);
        delete('{user}', ['as' => 'user.destroy', 'uses' => $controller . '@destroy'])->where(['user' => '[0-9]+']);

        get('{user}/attendance', ['as' => 'user.attendance.index', 'uses' => $attendanceController . '@index'])
            ->where(['user' => '[0-9]+']);
        put('{user}/attendance', ['as' => 'user.attendance.update', 'uses' => $attendanceController . '@update'])
            ->where(['user' => '[0-9]+']);
        delete('{user}/attendance/{group}/{year}', ['as' => 'user.attendance.destroy', 'uses' => $attendanceController . '@destroy'])
            ->where(['user' => '[0-9]+', 'group' => '[0-9]+', 'year' => '[0-9]+']);
    });