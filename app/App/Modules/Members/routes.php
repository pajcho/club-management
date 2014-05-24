<?php

    $controller = 'App\Modules\Members\Controllers\MemberController';
    $groupController = 'App\Modules\Members\Controllers\MemberGroupController';
    $groupDetailsController = 'App\Modules\Members\Controllers\MemberGroupDetailsController';

    /**
     * We are using this instead of resource routes just
     * to be able to have route names preserved
     * if there is future change in names
     */
    Route::group(array('prefix' => 'member', 'before' => 'auth'), function() use ($controller)
    {
        Route::get('/', array('as' => 'member.index', 'uses' => $controller . '@index'));
        Route::get('create', array('as' => 'member.create', 'uses' => $controller . '@create'));
        Route::post('/', array('as' => 'member.store', 'uses' => $controller . '@store'));
        Route::get('{member}', array('as' => 'member.show', 'uses' => $controller . '@show'));
        Route::get('{member}/edit', array('as' => 'member.edit', 'uses' => $controller . '@edit'));
        Route::put('{member}', array('as' => 'member.update', 'uses' => $controller . '@update'));
        Route::delete('{member}', array('as' => 'member.destroy', 'uses' => $controller . '@destroy'));
    });

    Route::group(array('prefix' => 'group', 'before' => 'auth'), function() use ($groupController, $groupDetailsController)
    {
        Route::get('/', array('as' => 'group.index', 'uses' => $groupController . '@index'));
        Route::get('create', array('as' => 'group.create', 'uses' => $groupController . '@create'));
        Route::post('/', array('as' => 'group.store', 'uses' => $groupController . '@store'));
        Route::get('{group}', array('as' => 'group.show', 'uses' => $groupController . '@show'));
        Route::get('{group}/edit', array('as' => 'group.edit', 'uses' => $groupController . '@edit'));
        Route::put('{group}', array('as' => 'group.update', 'uses' => $groupController . '@update'));
        Route::delete('{group}', array('as' => 'group.destroy', 'uses' => $groupController . '@destroy'));

        // Group PDF documents
        Route::get('{group}/attendance/{year}/{month}/{download?}', array('as' => 'group.attendance', 'uses' => $groupController . '@attendance'))
            ->where(array('year' => '[0-9]+', 'month' => '[0-9]+', 'download' => 'download'));
        Route::get('{group}/payments/{year}/{month}/{download?}', array('as' => 'group.payments', 'uses' => $groupController . '@payments'))
            ->where(array('year' => '[0-9]+', 'month' => '[0-9]+', 'download' => 'download'));

        // Group details (Payments & Attendance)
        Route::group(array('prefix' => '{group}/details'), function() use ($groupDetailsController)
        {
            Route::get('/', array('as' => 'group.details.index', 'uses' => $groupDetailsController . '@index'));
            Route::get('{year}/{month}', array('as' => 'group.details.show', 'uses' => $groupDetailsController . '@show'))
                ->where(array('year' => '[0-9]+', 'month' => '[0-9]+'));
            Route::put('{year}/{month}', array('as' => 'group.details.update', 'uses' => $groupDetailsController . '@update'))
                ->where(array('year' => '[0-9]+', 'month' => '[0-9]+'));
        });
    });