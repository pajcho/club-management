<?php namespace App;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->bindRepositories();

        // Include custom app helpers
        require_once __DIR__.'/helpers.php';
        require_once __DIR__.'/macros.php';

        // Load settings option in configuration file so
        // we don't make database queries every time
        $settings = $this->app->make('App\Repositories\Settings\SettingsRepositoryInterface');
        $this->app['config']->set('settings', $settings->getForConfig());
    }

    /**
     * Bind repositories.
     *
     * @return  void
     */
    protected function bindRepositories()
    {
        $this->app->singleton('App\Repositories\Member\MemberRepositoryInterface', 'App\Repositories\Member\DbMemberRepository');
        $this->app->singleton('App\Repositories\MemberGroup\MemberGroupRepositoryInterface', 'App\Repositories\MemberGroup\DbMemberGroupRepository');
        $this->app->singleton('App\Repositories\Settings\SettingsRepositoryInterface', 'App\Repositories\Settings\DbSettingsRepository');
    }

    public function register()
    {
        // Bind custom app classes
        $this->app->bind('App\Service\Theme', 'App\Service\Theme');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}
