<?php namespace Api\Modules\Users\Providers;

use Illuminate\Support\ServiceProvider;

class UsersProvider extends ServiceProvider
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