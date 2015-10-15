<?php namespace App\Modules\History\Providers;

use App\Modules\History\Repositories\DbHistoryRepository;
use App\Modules\History\Repositories\HistoryRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class HistoryProvider extends ServiceProvider
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
        $this->app->singleton(HistoryRepositoryInterface::class, DbHistoryRepository::class);
    }
}