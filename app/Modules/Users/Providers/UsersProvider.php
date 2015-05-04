<?php namespace App\Modules\Users\Providers;

use Illuminate\Support\ServiceProvider;

class UsersProvider extends ServiceProvider
{
    public function boot()
    {
        // Bring in the routes
        require __DIR__ . '/../routes.php';

        // Add dirs
        $this->app['view']->addLocation(__DIR__ . '/../Views');
    }

    public function register()
    {
        $this->app->bind(
            'App\Modules\Users\Repositories\UserRepositoryInterface',
            'App\Modules\Users\Repositories\DbUserRepository'
        );
        $this->app->bind(
            'App\Modules\Users\Repositories\UserGroupDataRepositoryInterface',
            'App\Modules\Users\Repositories\DbUserGroupDataRepository'
        );
    }
}