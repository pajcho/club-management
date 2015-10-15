<?php namespace App\Modules\Search\Providers;

use App\Modules\Search\Repositories\DbSearchRepository;
use App\Modules\Search\Repositories\SearchRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class SearchProvider extends ServiceProvider
{
    public function boot()
    {
        // Bring in the routes
        require __DIR__ . '/../routes.php';
    }

    public function register()
    {
        $this->app->singleton(SearchRepositoryInterface::class, DbSearchRepository::class);
    }
}
