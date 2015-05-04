<?php namespace App\Modules\Results\Providers;

use Illuminate\Support\ServiceProvider;

class ResultsProvider extends ServiceProvider
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
            'App\Modules\Results\Repositories\ResultRepositoryInterface',
            'App\Modules\Results\Repositories\DbResultRepository'
        );
        $this->app->singleton(
            'App\Modules\Results\Repositories\ResultCategoryRepositoryInterface',
            'App\Modules\Results\Repositories\DbResultCategoryRepository'
        );
    }
}