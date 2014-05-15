<?php namespace App\Modules\Settings\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SettingsProvider extends ServiceProvider
{
    public function boot()
    {
        // Bring in the routes
        require __DIR__ . '/../routes.php';

        // Add dirs
        View::addLocation(__DIR__ . '/../Views');
        Lang::addNamespace('settings', __DIR__ . '/../lang');
        Config::addNamespace('settings', __DIR__ . '/../config');

        // Load settings option in configuration file so
        // we don't make database queries every time
        $settings = $this->app->make('App\Modules\Settings\Repositories\SettingsRepositoryInterface');
        $this->app['config']->set('settings', $settings->getForConfig());
    }

    public function register()
    {
        $this->app->singleton(
            'App\Modules\Settings\Repositories\SettingsRepositoryInterface',
            'App\Modules\Settings\Repositories\DbSettingsRepository'
        );
    }
}