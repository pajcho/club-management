<?php

    $controller = 'App\Modules\Settings\Controllers\SettingsController';

    /**
     * We are using this instead of resource routes just
     * to be able to have route names preserved
     * if there is future change in names
     */
    Route::group(array('prefix' => 'settings', 'middleware' => 'auth'), function() use ($controller)
    {
        get('/', array('as' => 'settings.index', 'uses' => $controller . '@index'));
        get('/clear/cache', array('as' => 'settings.clear.cache', 'uses' => $controller . '@clearCache'));
        post('/', array('as' => 'settings.store', 'uses' => $controller . '@store'));
    });