<?php

    /**
     * We are using this instead of resource routes just
     * to be able to have route names preserved
     * if there is future change in names
     */
    Route::group(array('prefix' => 'history', 'before' => 'auth'), function()
    {
        Route::get('/', array('as' => 'history.index', 'uses' => 'App\Modules\History\Controllers\HistoryController@index'));
    });