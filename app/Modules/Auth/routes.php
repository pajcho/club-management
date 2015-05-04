<?php

    $controller = 'App\Modules\Auth\Controllers\AuthController';

    Route::group(array('prefix' => 'auth'), function() use ($controller)
    {
        get('login', array('as' => 'login', 'uses' => $controller . '@login'));
        post('login', array('as' => 'login', 'uses' => $controller . '@loginPost'));

        get('logout', array('as' => 'logout', 'uses' => $controller . '@logout'));
    });