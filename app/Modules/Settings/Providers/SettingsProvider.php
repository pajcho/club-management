<?php namespace App\Modules\Settings\Providers;

use Illuminate\Support\ServiceProvider;

class SettingsProvider extends ServiceProvider
{
    public function boot()
    {
        // Bring in the routes
        require __DIR__ . '/../routes.php';

        // Add dirs
        $this->app['view']->addLocation(__DIR__ . '/../Views');

        // Load settings option in configuration file so
        // we don't make database queries every time
        $settings = $this->app->make('App\Modules\Settings\Repositories\SettingsRepositoryInterface');
        $this->app['config']->set('settings', $settings->getForConfig());
    }

    public function register()
    {
        $this->app->bind(
            'App\Modules\Settings\Repositories\SettingsRepositoryInterface',
            'App\Modules\Settings\Repositories\DbSettingsRepository'
        );
    }
}