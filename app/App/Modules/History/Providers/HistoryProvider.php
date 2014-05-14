<?php namespace App\Modules\History\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class HistoryProvider extends ServiceProvider
{
    public function boot()
    {
        // Bring in the routes
        require __DIR__ . '/../routes.php';

        // Add dirs
        View::addLocation(__DIR__ . '/../Views');
        Lang::addNamespace('history', __DIR__ . '/../lang');
        Config::addNamespace('history', __DIR__ . '/../config');
    }

    public function register()
    {
        $this->app->singleton(
            'App\Modules\History\Repositories\HistoryRepositoryInterface',
            'App\Modules\History\Repositories\DbHistoryRepository'
        );
    }
}