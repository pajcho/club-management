<?php namespace App\Modules\Results\Providers;

use App\Modules\Results\Repositories\DbResultCategoryRepository;
use App\Modules\Results\Repositories\DbResultRepository;
use App\Modules\Results\Repositories\ResultCategoryRepositoryInterface;
use App\Modules\Results\Repositories\ResultRepositoryInterface;
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
        $this->app->singleton(ResultRepositoryInterface::class, DbResultRepository::class);
        $this->app->singleton(ResultCategoryRepositoryInterface::class, DbResultCategoryRepository::class);
    }
}