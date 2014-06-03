<?php namespace Api\Modules\Auth\Providers;

use Illuminate\Support\ServiceProvider;

class AuthProvider extends ServiceProvider
{
    public function boot()
    {
        // Bring in the routes
        require __DIR__ . '/../routes.php';
    }

    public function register()
    {
        //
    }
}