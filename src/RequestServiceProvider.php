<?php

namespace Noogic\LaravelRequest;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class RequestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/request.php' => config_path('request.php')
        ]);

        $pluginsPath = config('request.plugins_folder');

        if (! File::exists($pluginsPath)) {
            File::makeDirectory($pluginsPath, 0755, true);
        }

        $fileSystem = new Filesystem();
        $plugins = $fileSystem->allFiles($pluginsPath);

        /** @var ApplicationRequestPluginContainer $pluginContainer */
        $pluginContainer = $this->app->get(ApplicationRequestPluginContainer::class);

        foreach ($plugins as $plugin) {
            $namespace = config('request.plugins_namespace');
            $className = str_replace('.php', '', $plugin->getFilename());
            $class = $namespace . $className;

            $excludedPlugins = config('request.exclude_plugins');
            if(in_array($className, $excludedPlugins)) {
                continue;
            }

            $pluginContainer->register($class, $class::key());
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/request.php', 'request');

        $this->app->singleton(ApplicationRequestPluginContainer::class, function ($app) {
            return new ApplicationRequestPluginContainer();
        });
    }
}
