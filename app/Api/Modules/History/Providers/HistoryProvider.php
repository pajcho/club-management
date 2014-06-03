<?php namespace Api\Modules\History\Providers;

use Illuminate\Support\ServiceProvider;

class HistoryProvider extends ServiceProvider
{
    public function boot()
    {
        // Bring in the routes
        require __DIR__ . '/../routes.php';
    }

    public function register()
    {
        //
    }
}