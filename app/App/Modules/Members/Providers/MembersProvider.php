<?php namespace App\Modules\Members\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MembersProvider extends ServiceProvider
{
    public function boot()
    {
        // Bring in the routes
        require __DIR__ . '/../routes.php';

        // Add dirs
        View::addLocation(__DIR__ . '/../Views');
        Lang::addNamespace('members', __DIR__ . '/../lang');
        Config::addNamespace('members', __DIR__ . '/../config');
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