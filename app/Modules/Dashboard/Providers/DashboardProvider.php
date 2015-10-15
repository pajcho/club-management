<?php namespace App\Modules\Dashboard\Providers;

use App\Modules\Dashboard\Repositories\DashboardRepositoryInterface;
use App\Modules\Dashboard\Repositories\DbDashboardRepository;
use Illuminate\Support\ServiceProvider;

class DashboardProvider extends ServiceProvider
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
        $this->app->singleton(DashboardRepositoryInterface::class, DbDashboardRepository::class);
    }
}