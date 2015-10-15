<?php

    use App\Modules\Auth\Controllers\AuthController;

    Route::group(array('prefix' => 'auth'), function() {
        get('login', array('as' => 'login', 'uses' => AuthController::class . '@login'));
        post('login', array('as' => 'login', 'uses' => AuthController::class . '@loginPost'));

        get('logout', array('as' => 'logout', 'uses' => AuthController::class . '@logout'));
    });