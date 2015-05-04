<?php namespace App\Modules\Members\Providers;

use Illuminate\Support\ServiceProvider;

class MembersProvider extends ServiceProvider
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
        $this->app->singleton(
            'App\Modules\Members\Repositories\MemberRepositoryInterface',
            'App\Modules\Members\Repositories\DbMemberRepository'
        );
        $this->app->singleton(
            'App\Modules\Members\Repositories\MemberGroupRepositoryInterface',
            'App\Modules\Members\Repositories\DbMemberGroupRepository'
        );
    }
}