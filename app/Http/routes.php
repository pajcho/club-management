<?php

    use Illuminate\Support\Facades\Session;

    get('/', ['middleware' => 'auth', function () {
        Session::reflash();

        return redirect(route('member.index'));
    }]);