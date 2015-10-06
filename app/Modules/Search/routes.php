<?php

    $controller = 'App\Modules\Search\Controllers\SearchController';

    /**
     * We are using this instead of resource routes just
     * to be able to have route names preserved
     * if there is future change in names
     */
    Route::group(['prefix' => 'search', 'middleware' => 'auth'], function () use ($controller) {

        get('all', ['as' => 'search.all', 'uses' => $controller . '@all']);

    });
