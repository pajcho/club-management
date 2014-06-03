<?php

    $controller = 'Api\Modules\Members\Controllers\MemberController';
    $groupController = 'Api\Modules\Members\Controllers\MemberGroupController';
    $groupDetailsController = 'Api\Modules\Members\Controllers\MemberGroupDetailsController';

    // API routes
    Route::api(array('version' => 'v1', 'prefix' => 'api', 'protected' => true), function() use ($controller, $groupController, $groupDetailsController)
    {
        /**
         * We are using this instead of resource routes just
         * to be able to have route names preserved
         * if there is future change in names
         */
        Route::group(array('prefix' => 'members'), function() use ($controller)
        {
            Route::get('/', array('as' => 'members.index', 'uses' => $controller . '@index'));
            Route::get('filters', array('as' => 'members.filters', 'uses' => $controller . '@filters'));
            Route::get('create', array('as' => 'members.create', 'uses' => $controller . '@create'));
            Route::post('/', array('as' => 'members.store', 'uses' => $controller . '@store'));
            Route::get('{member}', array('as' => 'members.show', 'uses' => $controller . '@show'));
            Route::get('{member}/edit', array('as' => 'members.edit', 'uses' => $controller . '@edit'));
            Route::put('{member}', array('as' => 'members.update', 'uses' => $controller . '@update'));
            Route::delete('{member}', array('as' => 'members.destroy', 'uses' => $controller . '@destroy'));
        });

        Route::group(array('prefix' => 'groups'), function() use ($groupController, $groupDetailsController)
        {
            Route::get('/', array('as' => 'groups.index', 'uses' => $groupController . '@index'));
            Route::get('filters', array('as' => 'groups.filters', 'uses' => $groupController . '@filters'));
            Route::get('create', array('as' => 'groups.create', 'uses' => $groupController . '@create'));
            Route::post('/', array('as' => 'groups.store', 'uses' => $groupController . '@store'));
            Route::get('{group}', array('as' => 'groups.show', 'uses' => $groupController . '@show'));
            Route::get('{group}/edit', array('as' => 'groups.edit', 'uses' => $groupController . '@edit'));
            Route::put('{group}', array('as' => 'groups.update', 'uses' => $groupController . '@update'));
            Route::delete('{group}', array('as' => 'groups.destroy', 'uses' => $groupController . '@destroy'));

            // Group PDF documents
            Route::get('{group}/attendance/{year}/{month}/{download?}', array('as' => 'groups.attendance', 'uses' => $groupController . '@attendance'))
                ->where(array('year' => '[0-9]+', 'month' => '[0-9]+', 'download' => 'download'));
            Route::get('{group}/payments/{year}/{month}/{download?}', array('as' => 'groups.payments', 'uses' => $groupController . '@payments'))
                ->where(array('year' => '[0-9]+', 'month' => '[0-9]+', 'download' => 'download'));

            // Group details (Payments & Attendance)
            Route::group(array('prefix' => '{group}/details'), function() use ($groupDetailsController)
            {
                Route::get('/', array('as' => 'groups.details.index', 'uses' => $groupDetailsController . '@index'));
                Route::get('{year}/{month}', array('as' => 'groups.details.show', 'uses' => $groupDetailsController . '@show'))
                    ->where(array('year' => '[0-9]+', 'month' => '[0-9]+'));
                Route::put('{year}/{month}', array('as' => 'groups.details.update', 'uses' => $groupDetailsController . '@update'))
                    ->where(array('year' => '[0-9]+', 'month' => '[0-9]+'));
            });
        });
    });