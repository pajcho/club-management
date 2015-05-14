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
    Route::group(['prefix' => 'member', 'middleware' => 'auth'], function () use ($controller, $paymentsAndAttendanceController) {

        get('/', ['as' => 'member.index', 'uses' => $controller . '@index']);
        get('create', ['as' => 'member.create', 'uses' => $controller . '@create']);
        post('/', ['as' => 'member.store', 'uses' => $controller . '@store']);
        get('{member}', ['as' => 'member.show', 'uses' => $controller . '@show'])->where(['member' => '[0-9]+']);
        get('{member}/edit', ['as' => 'member.edit', 'uses' => $controller . '@edit'])->where(['member' => '[0-9]+']);
        put('{member}', ['as' => 'member.update', 'uses' => $controller . '@update'])->where(['member' => '[0-9]+']);
        delete('{member}', ['as' => 'member.destroy', 'uses' => $controller . '@destroy'])->where(['member' => '[0-9]+']);

        get('{member}/payments-and-attendance', ['as' => 'member.payments.index', 'uses' => $paymentsAndAttendanceController . '@index'])
            ->where(['member' => '[0-9]+']);
        put('{member}/payments-and-attendance', ['as' => 'member.payments.update', 'uses' => $paymentsAndAttendanceController . '@update'])
            ->where(['member' => '[0-9]+']);
        delete('{member}/payments-and-attendance/{group}/{year}/{month}', ['as' => 'member.payments.destroy', 'uses' => $paymentsAndAttendanceController . '@destroy'])
            ->where(['member' => '[0-9]+', 'group' => '[0-9]+', 'year' => '[0-9]+', 'month' => '[0-9]+']);

    });

    Route::group(['prefix' => 'group', 'middleware' => 'auth'], function () use ($groupController, $groupDataController) {

        get('/', ['as' => 'group.index', 'uses' => $groupController . '@index']);
        get('create', ['as' => 'group.create', 'uses' => $groupController . '@create']);
        post('/', ['as' => 'group.store', 'uses' => $groupController . '@store']);
        get('{group}', ['as' => 'group.show', 'uses' => $groupController . '@show'])->where(['group' => '[0-9]+']);
        get('{group}/edit', ['as' => 'group.edit', 'uses' => $groupController . '@edit'])->where(['group' => '[0-9]+']);
        put('{group}', ['as' => 'group.update', 'uses' => $groupController . '@update'])->where(['group' => '[0-9]+']);
        delete('{group}', ['as' => 'group.destroy', 'uses' => $groupController . '@destroy'])->where(['group' => '[0-9]+']);

        // Group PDF documents
        get('{group}/attendance/{year}/{month}/{download?}', ['as' => 'group.attendance', 'uses' => $groupController . '@attendance'])
            ->where(['group' => '[0-9]+', 'year' => '[0-9]+', 'month' => '[0-9]+', 'download' => 'download']);
        get('{group}/payments/{year}/{month}/{download?}', ['as' => 'group.payments', 'uses' => $groupController . '@payments'])
            ->where(['group' => '[0-9]+', 'year' => '[0-9]+', 'month' => '[0-9]+', 'download' => 'download']);

        // Group data (Payments & Attendance)
        Route::group(['prefix' => '{group}/data'], function () use ($groupDataController) {

            get('/', ['as' => 'group.data.index', 'uses' => $groupDataController . '@index']);
            get('{year}/{month}', ['as' => 'group.data.show', 'uses' => $groupDataController . '@show'])
                ->where(['year' => '[0-9]+', 'month' => '[0-9]+']);
            put('{year}/{month}', ['as' => 'group.data.update', 'uses' => $groupDataController . '@update'])
                ->where(['year' => '[0-9]+', 'month' => '[0-9]+']);

            // AJAX route to get number of members that have already payed membership for month
            get('{year}/{month}/payment-data', ['as' => 'group.data.get.payment-data', 'uses' => $groupDataController . '@getPaymentData'])
                ->where(['year' => '[0-9]+', 'month' => '[0-9]+']);

        });

    });