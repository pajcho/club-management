<?php

    $controller = 'App\Modules\Users\Controllers\UserController';
    $attendanceController = 'App\Modules\Users\Controllers\UserAttendanceController';

    /**
     * We are using this instead of resource routes just
     * to be able to have route names preserved
     * if there is future change in names
     */
    Route::group(['prefix' => 'user', 'before' => 'auth'], function () use ($controller, $attendanceController) {
        Route::get('/', ['as' => 'user.index', 'uses' => $controller . '@index']);
        Route::get('create', ['as' => 'user.create', 'uses' => $controller . '@create']);
        Route::post('/', ['as' => 'user.store', 'uses' => $controller . '@store']);
        Route::get('{user}', ['as' => 'user.show', 'uses' => $controller . '@show'])->where(['user' => '[0-9]+']);
        Route::get('{user}/edit', ['as' => 'user.edit', 'uses' => $controller . '@edit'])->where(['user' => '[0-9]+']);
        Route::put('{user}', ['as' => 'user.update', 'uses' => $controller . '@update'])->where(['user' => '[0-9]+']);
        Route::delete('{user}', ['as' => 'user.destroy', 'uses' => $controller . '@destroy'])->where(['user' => '[0-9]+']);

        Route::get('{user}/attendance', ['as' => 'user.attendance.index', 'uses' => $attendanceController . '@index'])
            ->where(['user' => '[0-9]+']);
        Route::put('{user}/attendance', ['as' => 'user.attendance.update', 'uses' => $attendanceController . '@update'])
            ->where(['user' => '[0-9]+']);
    });