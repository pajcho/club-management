<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

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
        $this->loadModules();

        // Include custom app helpers
        require_once app_path('helpers.php');
        require_once app_path('macros.php');
    }

    /**
     * Load modules by registering their service providers
     *
     * @return  void
     */
    protected function loadModules()
    {
        // Make sure that path is without trailing slash
        $modulesRoot = rtrim($this->app['config']['club-management.modules_folder'], '/');

        $files = $this->app['files']->files(app_path($modulesRoot . '/*/Providers'));

        // If there are no files than exit
        if (!$files) return;

        foreach ($files as $file) {
            // get module part of name
            $className = last(explode($modulesRoot, $file, 2));

            // Add module root, and remove php extension
            $className = substr($modulesRoot . $className, 0, -4);

            // Make valid class namespace
            $className = 'App\\' . str_replace('/', '\\', $className);

            // Register module provider
            $this->app->register($className);
        }
    }

    public function register()
    {
        // Bind custom app classes
        $this->app->bind('App\Services\Theme', 'App\Services\Theme');
        $this->app->bind('Illuminate\Contracts\Auth\Registrar', 'App\Services\Registrar');
    }
}

