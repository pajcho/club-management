<?php namespace Api\Modules\Settings\Providers;

use Illuminate\Support\ServiceProvider;

class SettingsProvider extends ServiceProvider
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