<?php

    use App\Modules\Search\Controllers\SearchController;

    /**
     * We are using this instead of resource routes just
     * to be able to have route names preserved
     * if there is future change in names
     */
    Route::group(['prefix' => 'search', 'middleware' => 'auth'], function () {

        get('all', ['as' => 'search.all', 'uses' => SearchController::class . '@all']);

    });
