<?php

namespace LaravelGuru\LaravelFilehandler;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use LaravelGuru\LaravelFilehandler\Services\FileManager;
use LaravelGuru\LaravelFilehandler\Services\FileService;

class LaravelFilehandlerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-filehandler');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-filehandler');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->registerRoutes();


        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-filehandler.php'),
            ], 'config');

            if (!class_exists('File')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_files_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_files_table.php'),
                    // you can add any number of migrations here
                ], 'migration');
            }

            $this->publishes([
                __DIR__ . '/app/Http/Controllers' => app_path('Http/Controllers'),
            ], 'controller');

            $this->publishes([
                __DIR__ . '/app/Http/Resources' => app_path('Http/Resources'),
            ], 'resource');

            $this->publishes([
                __DIR__ . '/app/Models' => app_path('Models'),
            ], 'model');

            if (!is_dir(resource_path('js/Components/upload'))) {
                mkdir(resource_path('js/Components/upload'), 0755, true);
            }

            $this->publishes([
                __DIR__ . '/resources/js/Components' => resource_path('js/Components/upload'),
            ], 'components');

            $this->publishes([
                __DIR__ . '/resources/css' => resource_path('css'),
            ], 'css');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-filehandler'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-filehandler'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-filehandler'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-filehandler');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-filehandler', function ($app) {
            return FileService::getInstance($app->make(FileManager::class));
        });
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => config('api.prefix'),
            'middleware' => config('api.middleware'),
        ];
    }
}
