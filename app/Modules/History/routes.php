<?php

    use App\Modules\History\Controllers\HistoryController;

    /**
     * We are using this instead of resource routes just
     * to be able to have route names preserved
     * if there is future change in names
     */
    Route::group(array('prefix' => 'history', 'middleware' => 'auth'), function() {

        get('/', array('as' => 'history.index', 'uses' => HistoryController::class . '@index'));

    });