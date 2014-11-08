<?php

    $controller = 'App\Modules\Members\Controllers\MemberController';
    $groupController = 'App\Modules\Members\Controllers\MemberGroupController';
    $groupDataController = 'App\Modules\Members\Controllers\MemberGroupDataController';
    $paymentsAndAttendanceController = 'App\Modules\Members\Controllers\MemberPaymentsAndAttendanceController';

    /**
     * We are using this instead of resource routes just
     * to be able to have route names preserved
     * if there is future change in names
     */
    Route::group(['prefix' => 'member', 'before' => 'auth'], function () use ($controller, $paymentsAndAttendanceController) {

        Route::get('/', ['as' => 'member.index', 'uses' => $controller . '@index']);
        Route::get('create', ['as' => 'member.create', 'uses' => $controller . '@create']);
        Route::post('/', ['as' => 'member.store', 'uses' => $controller . '@store']);
        Route::get('{member}', ['as' => 'member.show', 'uses' => $controller . '@show']);
        Route::get('{member}/edit', ['as' => 'member.edit', 'uses' => $controller . '@edit']);
        Route::put('{member}', ['as' => 'member.update', 'uses' => $controller . '@update']);
        Route::delete('{member}', ['as' => 'member.destroy', 'uses' => $controller . '@destroy']);

        Route::get('{member}/payments-and-attendance', ['as' => 'member.payments.index', 'uses' => $paymentsAndAttendanceController . '@index']);
        Route::put('{member}/payments-and-attendance/{year}/{month}', ['as' => 'member.payments.update', 'uses' => $paymentsAndAttendanceController . '@update'])
            ->where(['year' => '[0-9]+', 'month' => '[0-9]+']);

    });

    Route::group(['prefix' => 'group', 'before' => 'auth'], function () use ($groupController, $groupDataController) {

        Route::get('/', ['as' => 'group.index', 'uses' => $groupController . '@index']);
        Route::get('create', ['as' => 'group.create', 'uses' => $groupController . '@create']);
        Route::post('/', ['as' => 'group.store', 'uses' => $groupController . '@store']);
        Route::get('{group}', ['as' => 'group.show', 'uses' => $groupController . '@show']);
        Route::get('{group}/edit', ['as' => 'group.edit', 'uses' => $groupController . '@edit']);
        Route::put('{group}', ['as' => 'group.update', 'uses' => $groupController . '@update']);
        Route::delete('{group}', ['as' => 'group.destroy', 'uses' => $groupController . '@destroy']);

        // Group PDF documents
        Route::get('{group}/attendance/{year}/{month}/{download?}', ['as' => 'group.attendance', 'uses' => $groupController . '@attendance'])
            ->where(['year' => '[0-9]+', 'month' => '[0-9]+', 'download' => 'download']);
        Route::get('{group}/payments/{year}/{month}/{download?}', ['as' => 'group.payments', 'uses' => $groupController . '@payments'])
            ->where(['year' => '[0-9]+', 'month' => '[0-9]+', 'download' => 'download']);

        // Group data (Payments & Attendance)
        Route::group(['prefix' => '{group}/data'], function () use ($groupDataController) {

            Route::get('/', ['as' => 'group.data.index', 'uses' => $groupDataController . '@index']);
            Route::get('{year}/{month}', ['as' => 'group.data.show', 'uses' => $groupDataController . '@show'])
                ->where(['year' => '[0-9]+', 'month' => '[0-9]+']);
            Route::put('{year}/{month}', ['as' => 'group.data.update', 'uses' => $groupDataController . '@update'])
                ->where(['year' => '[0-9]+', 'month' => '[0-9]+']);

            // AJAX route to get number of members that have already payed membership for month
            Route::get('{year}/{month}/payment-data', ['as' => 'group.data.get.payment-data', 'uses' => $groupDataController . '@getPaymentData'])
                ->where(['year' => '[0-9]+', 'month' => '[0-9]+']);
            
        });

    });