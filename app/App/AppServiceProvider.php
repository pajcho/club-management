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
    }

    /**
     * Bind repositories.
     *
     * @return  void
     */
    protected function bindRepositories()
    {
        $this->app->singleton('App\Repositories\Member\MemberRepositoryInterface', 'App\Repositories\Member\DbMemberRepository');
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
