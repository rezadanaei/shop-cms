<?php

namespace Danaei\ShopCMS;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Danaei\ShopCMS\Http\Middleware\MetaTagsMiddleware;


class ShopCMSServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/shopcms.php', 'shopcms');
    }

    public function boot(Router $router)
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('meta-tags', \Danaei\ShopCMS\Http\Middleware\MetaTagsMiddleware::class);

        $this->publishes([
            __DIR__.'/../config/shopcms.php' => config_path('shopcms.php'),
        ], 'shopcms-config');

        $this->publishes([
            __DIR__.'/../routes/web.php' => base_path('routes/shop-cms.php'),
        ], 'shopcms-routes');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Danaei\ShopCMS\Commands\InstallCMSCommand::class,
                \Danaei\ShopCMS\Commands\CreateCmsFileCommand::class,
            ]);
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}