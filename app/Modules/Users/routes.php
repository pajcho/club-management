<?php

    use App\Modules\Users\Controllers\UserAttendanceController;
    use App\Modules\Users\Controllers\UserController;

    /**
     * We are using this instead of resource routes just
     * to be able to have route names preserved
     * if there is future change in names
     */
    Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
        get('/', ['as' => 'user.index', 'uses' => UserController::class . '@index']);
        get('create', ['as' => 'user.create', 'uses' => UserController::class . '@create']);
        post('/', ['as' => 'user.store', 'uses' => UserController::class . '@store']);
        get('{user}', ['as' => 'user.show', 'uses' => UserController::class . '@show'])->where(['user' => '[0-9]+']);
        get('{user}/edit', ['as' => 'user.edit', 'uses' => UserController::class . '@edit'])->where(['user' => '[0-9]+']);
        put('{user}', ['as' => 'user.update', 'uses' => UserController::class . '@update'])->where(['user' => '[0-9]+']);
        delete('{user}', ['as' => 'user.destroy', 'uses' => UserController::class . '@destroy'])->where(['user' => '[0-9]+']);

        get('{user}/attendance', ['as' => 'user.attendance.index', 'uses' => UserAttendanceController::class . '@index'])
            ->where(['user' => '[0-9]+']);
        get('{user}/attendance/{year}/{month}', ['as' => 'user.attendance.show', 'uses' => UserAttendanceController::class . '@show'])
            ->where(['user' => '[0-9]+', 'year' => '[0-9]+', 'month' => '[0-9]+']);
        put('{user}/attendance', ['as' => 'user.attendance.update', 'uses' => UserAttendanceController::class . '@update'])
            ->where(['user' => '[0-9]+']);
        delete('{user}/attendance/{group}/{year}', ['as' => 'user.attendance.destroy', 'uses' => UserAttendanceController::class . '@destroy'])
            ->where(['user' => '[0-9]+', 'group' => '[0-9]+', 'year' => '[0-9]+']);
    });