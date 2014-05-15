<?php

    Route::get('/', array('before' => 'auth', function()
    {
        Session::reflash();
        return Redirect::route('member.index');
    }));