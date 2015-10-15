<?php namespace App\Modules\Members\Providers;

use App\Modules\Members\Repositories\DbMemberGroupRepository;
use App\Modules\Members\Repositories\DbMemberRepository;
use App\Modules\Members\Repositories\MemberGroupRepositoryInterface;
use App\Modules\Members\Repositories\MemberRepositoryInterface;
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
        $this->app->singleton(MemberRepositoryInterface::class, DbMemberRepository::class);
        $this->app->singleton(MemberGroupRepositoryInterface::class, DbMemberGroupRepository::class);
    }
}