<?php

    use App\Modules\Results\Controllers\ResultCategoryController;
    use App\Modules\Results\Controllers\ResultController;

    /**
     * We are using this instead of resource routes just
     * to be able to have route names preserved
     * if there is future change in names
     */
    Route::group(array('prefix' => 'result', 'middleware' => 'auth'), function() {
        Route::group(array('prefix' => 'category'), function() {
            get('/', array('as' => 'result.category.index', 'uses' => ResultCategoryController::class . '@index'));
            get('create', array('as' => 'result.category.create', 'uses' => ResultCategoryController::class . '@create'));
            post('/', array('as' => 'result.category.store', 'uses' => ResultCategoryController::class . '@store'));
            get('{category}', array('as' => 'result.category.show', 'uses' => ResultCategoryController::class . '@show'));
            get('{category}/edit', array('as' => 'result.category.edit', 'uses' => ResultCategoryController::class . '@edit'));
            put('{category}', array('as' => 'result.category.update', 'uses' => ResultCategoryController::class . '@update'));
            delete('{category}', array('as' => 'result.category.destroy', 'uses' => ResultCategoryController::class . '@destroy'));
        });

        get('/', array('as' => 'result.index', 'uses' => ResultController::class . '@index'));
        get('create', array('as' => 'result.create', 'uses' => ResultController::class . '@create'));
        post('/', array('as' => 'result.store', 'uses' => ResultController::class . '@store'));
        get('{result}', array('as' => 'result.show', 'uses' => ResultController::class . '@show'));
        get('{result}/edit', array('as' => 'result.edit', 'uses' => ResultController::class . '@edit'));
        put('{result}', array('as' => 'result.update', 'uses' => ResultController::class . '@update'));
        delete('{result}', array('as' => 'result.destroy', 'uses' => ResultController::class . '@destroy'));
    });