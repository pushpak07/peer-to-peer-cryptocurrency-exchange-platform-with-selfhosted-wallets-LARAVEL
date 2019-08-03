<?php

namespace HolluwaTosin\Installer;

use HolluwaTosin\Installer\Commands\FreshInstall;
use HolluwaTosin\Installer\Middleware\CanInstall;
use HolluwaTosin\Installer\Middleware\CanUpdate;
use HolluwaTosin\Installer\Middleware\CanVerify;
use HolluwaTosin\Installer\Middleware\ValidateSession;
use HolluwaTosin\Installer\Middleware\Verification;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class InstallerServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        $router->middlewareGroup('installer.verify', [Verification::class]);
        $router->middlewareGroup('installer.can_install', [CanInstall::class]);
        $router->middlewareGroup('installer.can_update', [CanUpdate::class]);
        $router->middlewareGroup('installer.validate_session', [ValidateSession::class]);
        $router->middlewareGroup('installer.can_verify', [CanVerify::class]);

        if ($this->app->runningInConsole()) {$this->bootForConsole();}

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'installer');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'installer');
        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }

    /**
     * Register any package services.
     *
     * @param $cache
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/installer.php', 'installer'
        );

        $this->app->singleton('installer', function ($app){
            return new Installer($app->make('Illuminate\Cache\Repository'));
        });
    }

//    /**
//     * Get the services provided by the provider.
//     *
//     * @return array
//     */
//    public function provides()
//    {
//        return ['installer'];
//    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/installer.php' => config_path('installer.php'),
        ], 'installer.config');

        $this->publishes([
            __DIR__.'/../assets' => public_path('vendor/installer'),
        ], 'installer.views');

//        $this->publishes([
//            __DIR__.'/../resources/views' => base_path('resources/views/vendor/installer'),
//        ], 'installer.views');


        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/installer'),
        ], 'installer.views');*/

        // Registering package commands.
        $this->commands([FreshInstall::class]);
    }
}
