<?php namespace App\Modules\Users\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class UsersProvider extends ServiceProvider
{
    public function boot()
    {
        // Bring in the routes
        require __DIR__ . '/../routes.php';

        // Add dirs
        View::addLocation(__DIR__ . '/../Views');
        Lang::addNamespace('users', __DIR__ . '/../lang');
        Config::addNamespace('users', __DIR__ . '/../config');
    }

    public function register()
    {
        $this->app->singleton(
            'App\Modules\Users\Repositories\UserRepositoryInterface',
            'App\Modules\Users\Repositories\DbUserRepository'
        );
        $this->app->singleton(
            'App\Modules\Users\Repositories\UserGroupDataRepositoryInterface',
            'App\Modules\Users\Repositories\DbUserGroupDataRepository'
        );
    }
}