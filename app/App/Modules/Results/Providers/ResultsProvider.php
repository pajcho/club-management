<?php namespace App\Modules\Results\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ResultsProvider extends ServiceProvider
{
    public function boot()
    {
        // Bring in the routes
        require __DIR__ . '/../routes.php';

        // Add dirs
        View::addLocation(__DIR__ . '/../Views');
        Lang::addNamespace('results', __DIR__ . '/../lang');
        Config::addNamespace('results', __DIR__ . '/../config');
    }

    public function register()
    {
        $this->app->singleton(
            'App\Modules\Results\Repositories\ResultRepositoryInterface',
            'App\Modules\Results\Repositories\DbResultRepository'
        );
        $this->app->singleton(
            'App\Modules\Results\Repositories\ResultCategoryRepositoryInterface',
            'App\Modules\Results\Repositories\DbResultCategoryRepository'
        );
    }
}