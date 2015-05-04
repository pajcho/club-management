<?php

    $controller = 'App\Modules\Dashboard\Controllers\DashboardController';

    /**
     * We are using this instead of resource routes just
     * to be able to have route names preserved
     * if there is future change in names
     */
    Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function () use ($controller) {

        get('/', ['as' => 'dashboard.index', 'uses' => $controller . '@index']);

    });