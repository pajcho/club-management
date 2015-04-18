<?php

    $controller = 'App\Modules\Results\Controllers\ResultController';
    $categoryController = 'App\Modules\Results\Controllers\ResultCategoryController';

    /**
     * We are using this instead of resource routes just
     * to be able to have route names preserved
     * if there is future change in names
     */
    Route::group(array('prefix' => 'result', 'middleware' => 'auth'), function() use ($controller, $categoryController)
    {
        Route::group(array('prefix' => 'category'), function() use ($categoryController)
        {
            get('/', array('as' => 'result.category.index', 'uses' => $categoryController . '@index'));
            get('create', array('as' => 'result.category.create', 'uses' => $categoryController . '@create'));
            post('/', array('as' => 'result.category.store', 'uses' => $categoryController . '@store'));
            get('{category}', array('as' => 'result.category.show', 'uses' => $categoryController . '@show'));
            get('{category}/edit', array('as' => 'result.category.edit', 'uses' => $categoryController . '@edit'));
            put('{category}', array('as' => 'result.category.update', 'uses' => $categoryController . '@update'));
            delete('{category}', array('as' => 'result.category.destroy', 'uses' => $categoryController . '@destroy'));
        });

        get('/', array('as' => 'result.index', 'uses' => $controller . '@index'));
        get('create', array('as' => 'result.create', 'uses' => $controller . '@create'));
        post('/', array('as' => 'result.store', 'uses' => $controller . '@store'));
        get('{result}', array('as' => 'result.show', 'uses' => $controller . '@show'));
        get('{result}/edit', array('as' => 'result.edit', 'uses' => $controller . '@edit'));
        put('{result}', array('as' => 'result.update', 'uses' => $controller . '@update'));
        delete('{result}', array('as' => 'result.destroy', 'uses' => $controller . '@destroy'));
    });