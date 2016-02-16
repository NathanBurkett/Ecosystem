<?php

namespace RoadworkRah\Ecosystem\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use RoadworkRah\Ecosystem\Middleware\CheckEcosystemAction;

class EcosystemServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the package service
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        $router->middleware('ecosystem', \RoadworkRah\Ecosystem\Middleware\CheckEcosystemAction::class);
        $this->publishConfig();
        $this->mergeAppConfigWithPackageConfig();
    }

    /**
     * Register the application services
     * @return void
     */
    public function register()
    {
        $this->registerHtmlSupport();
        $this->registerEcosystemGenerator();
    }

    /**
     * Publish package config file
     * @return void
     */
    private function publishConfig()
    {
        $this->publishes([
            __DIR__.'/../config/ecosystem.php' => config_path('ecosystem.php')
        ], 'config');
    }

    /**
     * Register HtmlBuilder
     * @return void
     */
    private function registerHtmlSupport()
    {
        $this->app->bind(
            '\RoadworkRah\Ecosystem\Contracts\HtmlOutputContract',
            '\RoadworkRah\Ecosystem\Builders\HtmlBuilder'
        );
    }

    /**
     * Register Ecosystem Generator command
     * @return void
     */
    private function registerEcosystemGenerator()
    {
        $this->app->singleton('command.roadworkrah.ecosystem', function ($app) {
            return $app['RoadworkRah\Ecosystem\Commands\GenerateNewEcosystemCommand'];
        });

        $this->commands('command.roadworkrah.ecosystem');
    }

    /**
     * Allow application config to override package config
     * @return void
     */
    private function mergeAppConfigWithPackageConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/ecosystem.php',
            'ecosystem'
        );
    }

    /**
     * Package service name
     * @return array
     */
    public function provides()
    {
        return ['ecosystem'];
    }
}
