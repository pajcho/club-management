<?php namespace App\Modules\Dashboard\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class DashboardProvider extends ServiceProvider
{
    public function boot()
    {
        // Bring in the routes
        require __DIR__ . '/../routes.php';

        // Add dirs
        View::addLocation(__DIR__ . '/../Views');
    }

    public function register()
    {
        $this->app->singleton(
            'App\Modules\Dashboard\Repositories\DashboardRepositoryInterface',
            'App\Modules\Dashboard\Repositories\DbDashboardRepository'
        );
    }
}