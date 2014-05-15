<?php

    $controller = 'App\Modules\Auth\Controllers\AuthController';

    Route::group(array('prefix' => 'auth'), function() use ($controller)
    {
        Route::get('login', array('as' => 'login', 'uses' => $controller . '@login'));
        Route::post('login', array('as' => 'login', 'uses' => $controller . '@loginPost'));

        Route::get('logout', array('as' => 'logout', 'uses' => $controller . '@logout'));
    });