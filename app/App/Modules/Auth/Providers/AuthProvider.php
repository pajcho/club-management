<?php namespace App\Modules\Auth\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AuthProvider extends ServiceProvider
{
    public function boot()
    {
        // Bring in the routes
        require __DIR__ . '/../routes.php';

        // Add dirs
        View::addLocation(__DIR__ . '/../Views');
        Lang::addNamespace('auth', __DIR__ . '/../lang');
        Config::addNamespace('auth', __DIR__ . '/../config');
    }

    public function register()
    {
        //
    }
}