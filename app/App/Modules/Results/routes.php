<?php

    $controller = 'App\Modules\Results\Controllers\ResultController';
    $categoryController = 'App\Modules\Results\Controllers\ResultCategoryController';

    /**
     * We are using this instead of resource routes just
     * to be able to have route names preserved
     * if there is future change in names
     */
    Route::group(array('prefix' => 'result', 'before' => 'auth'), function() use ($controller, $categoryController)
    {
        Route::group(array('prefix' => 'category'), function() use ($categoryController)
        {
            Route::get('/', array('as' => 'result.category.index', 'uses' => $categoryController . '@index'));
            Route::get('create', array('as' => 'result.category.create', 'uses' => $categoryController . '@create'));
            Route::post('/', array('as' => 'result.category.store', 'uses' => $categoryController . '@store'));
            Route::get('{category}', array('as' => 'result.category.show', 'uses' => $categoryController . '@show'));
            Route::get('{category}/edit', array('as' => 'result.category.edit', 'uses' => $categoryController . '@edit'));
            Route::put('{category}', array('as' => 'result.category.update', 'uses' => $categoryController . '@update'));
            Route::delete('{category}', array('as' => 'result.category.destroy', 'uses' => $categoryController . '@destroy'));
        });

        Route::get('/', array('as' => 'result.index', 'uses' => $controller . '@index'));
        Route::get('create', array('as' => 'result.create', 'uses' => $controller . '@create'));
        Route::post('/', array('as' => 'result.store', 'uses' => $controller . '@store'));
        Route::get('{result}', array('as' => 'result.show', 'uses' => $controller . '@show'));
        Route::get('{result}/edit', array('as' => 'result.edit', 'uses' => $controller . '@edit'));
        Route::put('{result}', array('as' => 'result.update', 'uses' => $controller . '@update'));
        Route::delete('{result}', array('as' => 'result.destroy', 'uses' => $controller . '@destroy'));
    });