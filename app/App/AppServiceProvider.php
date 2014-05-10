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
        $settings = $this->app->make('App\Repositories\SettingsRepositoryInterface');
        $this->app['config']->set('settings', $settings->getForConfig());
    }

    /**
     * Bind repositories.
     *
     * @return  void
     */
    protected function bindRepositories()
    {
        $this->app->singleton('App\Repositories\MemberRepositoryInterface', 'App\Repositories\DbMemberRepository');
        $this->app->singleton('App\Repositories\MemberGroupRepositoryInterface', 'App\Repositories\DbMemberGroupRepository');
        $this->app->singleton('App\Repositories\SettingsRepositoryInterface', 'App\Repositories\DbSettingsRepository');
        $this->app->singleton('App\Repositories\UserRepositoryInterface', 'App\Repositories\DbUserRepository');
        $this->app->singleton('App\Repositories\HistoryRepositoryInterface', 'App\Repositories\DbHistoryRepository');
        $this->app->singleton('App\Repositories\ResultRepositoryInterface', 'App\Repositories\DbResultRepository');
        $this->app->singleton('App\Repositories\ResultCategoryRepositoryInterface', 'App\Repositories\DbResultCategoryRepository');
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
