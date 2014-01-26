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

    public function register(){}

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
