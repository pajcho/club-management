<?php namespace Api\Modules\Results\Providers;

use Illuminate\Support\ServiceProvider;

class ResultsProvider extends ServiceProvider
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