<?php namespace App\Modules\Settings\Providers;

use App\Modules\Settings\Repositories\DbSettingsRepository;
use App\Modules\Settings\Repositories\SettingsRepositoryInterface;
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
        $settings = $this->app->make(SettingsRepositoryInterface::class);
        $this->app['config']->set('settings', $settings->getForConfig());
    }

    public function register()
    {
        $this->app->bind(SettingsRepositoryInterface::class, DbSettingsRepository::class);
    }
}