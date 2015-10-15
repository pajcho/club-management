<?php

    use App\Modules\Members\Controllers\MemberController;
    use App\Modules\Members\Controllers\MemberGroupController;
    use App\Modules\Members\Controllers\MemberGroupDataController;
    use App\Modules\Members\Controllers\MemberPaymentsAndAttendanceController;

    /**
     * We are using this instead of resource routes just
     * to be able to have route names preserved
     * if there is future change in names
     */
    Route::group(['prefix' => 'member', 'middleware' => 'auth'], function () {

        get('/', ['as' => 'member.index', 'uses' => MemberController::class . '@index']);
        get('create', ['as' => 'member.create', 'uses' => MemberController::class . '@create']);
        post('/', ['as' => 'member.store', 'uses' => MemberController::class . '@store']);
        get('{member}', ['as' => 'member.show', 'uses' => MemberController::class . '@show'])->where(['member' => '[0-9]+']);
        get('{member}/edit', ['as' => 'member.edit', 'uses' => MemberController::class . '@edit'])->where(['member' => '[0-9]+']);
        put('{member}', ['as' => 'member.update', 'uses' => MemberController::class . '@update'])->where(['member' => '[0-9]+']);
        delete('{member}', ['as' => 'member.destroy', 'uses' => MemberController::class . '@destroy'])->where(['member' => '[0-9]+']);

        get('{member}/payments-and-attendance', ['as' => 'member.payments.index', 'uses' => MemberPaymentsAndAttendanceController::class . '@index'])
            ->where(['member' => '[0-9]+']);
        put('{member}/payments-and-attendance', ['as' => 'member.payments.update', 'uses' => MemberPaymentsAndAttendanceController::class . '@update'])
            ->where(['member' => '[0-9]+']);
        delete('{member}/payments-and-attendance/{group}/{year}/{month}', ['as' => 'member.payments.destroy', 'uses' => MemberPaymentsAndAttendanceController::class . '@destroy'])
            ->where(['member' => '[0-9]+', 'group' => '[0-9]+', 'year' => '[0-9]+', 'month' => '[0-9]+']);

    });

    Route::group(['prefix' => 'group', 'middleware' => 'auth'], function () {

        get('/', ['as' => 'group.index', 'uses' => MemberGroupController::class . '@index']);
        get('create', ['as' => 'group.create', 'uses' => MemberGroupController::class . '@create']);
        post('/', ['as' => 'group.store', 'uses' => MemberGroupController::class . '@store']);
        get('{group}', ['as' => 'group.show', 'uses' => MemberGroupController::class . '@show'])->where(['group' => '[0-9]+']);
        get('{group}/edit', ['as' => 'group.edit', 'uses' => MemberGroupController::class . '@edit'])->where(['group' => '[0-9]+']);
        put('{group}', ['as' => 'group.update', 'uses' => MemberGroupController::class . '@update'])->where(['group' => '[0-9]+']);
        delete('{group}', ['as' => 'group.destroy', 'uses' => MemberGroupController::class . '@destroy'])->where(['group' => '[0-9]+']);

        // Group PDF documents
        get('{group}/attendance/{year}/{month}/{download?}', ['as' => 'group.attendance', 'uses' => MemberGroupController::class . '@attendance'])
            ->where(['group' => '[0-9]+', 'year' => '[0-9]+', 'month' => '[0-9]+', 'download' => 'download']);
        get('{group}/payments/{year}/{month}/{download?}', ['as' => 'group.payments', 'uses' => MemberGroupController::class . '@payments'])
            ->where(['group' => '[0-9]+', 'year' => '[0-9]+', 'month' => '[0-9]+', 'download' => 'download']);

        // Group data (Payments & Attendance)
        Route::group(['prefix' => '{group}/data'], function () {

            get('/', ['as' => 'group.data.index', 'uses' => MemberGroupDataController::class . '@index']);
            get('{year}/{month}', ['as' => 'group.data.show', 'uses' => MemberGroupDataController::class . '@show'])
                ->where(['year' => '[0-9]+', 'month' => '[0-9]+']);
            put('{year}/{month}', ['as' => 'group.data.update', 'uses' => MemberGroupDataController::class . '@update'])
                ->where(['year' => '[0-9]+', 'month' => '[0-9]+']);

            // AJAX route to get number of members that have already payed membership for month
            get('{year}/{month}/payment-data', ['as' => 'group.data.get.payment-data', 'uses' => MemberGroupDataController::class . '@getPaymentData'])
                ->where(['year' => '[0-9]+', 'month' => '[0-9]+']);

        });

    });