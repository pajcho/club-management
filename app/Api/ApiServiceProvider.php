<?php namespace Api;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider {

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
        $this->loadModules();
    }

    /**
     * Bind repositories.
     *
     * @return  void
     */
    protected function bindRepositories()
    {
        // Bind global repositories if there are any
    }

    /**
     * Load modules by registering their service providers
     *
     * @return  void
     */
    protected function loadModules()
    {
        $modulesRoot = $this->app->config['club-management.api.modules_folder'];

        $files = File::files(app_path($modulesRoot . '*/Providers'));

        // If there are no files than exit
        if(!$files) return;

        foreach($files as $file)
        {
            // get module part of name
            $className = last(explode($modulesRoot, $file, 2));

            // Add module root, and remove php extension
            $className = substr($modulesRoot . $className, 0, -4);

            // Make valid class namespace
            $className = str_replace('/', '\\', $className);

            // Register module provider
            $this->app->register($className);
        }
    }

    public function register()
    {
        // Bind custom app classes
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
