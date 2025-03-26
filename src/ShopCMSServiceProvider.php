<?php

namespace Danaei\ShopCMS;

use Illuminate\Support\ServiceProvider;

class ShopCMSServiceProvider extends ServiceProvider
{
    public function register()
    {
        // ثبت کانفیگ
        $this->mergeConfigFrom(__DIR__.'/../config/shopcms.php', 'shopcms');
    }

    public function boot()
    {
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
                \Danaei\ShopCMS\Commands\CreateBladeTemplateCommand::class,
            ]);
        }
    
        // اضافه کردن Middleware به گروه 'web'
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', \App\Http\Middleware\MetaTagsMiddleware::class);
    }

}
