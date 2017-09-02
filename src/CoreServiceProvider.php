<?php

namespace Mcms\Core;

use App;
use Mcms\Core\Middleware\Cors;
use Mcms\Core\StartUp\RegisterDirectives;
use Mcms\Core\StartUp\RegisterEvents;
use Mcms\Core\StartUp\RegisterFacades;
use Mcms\Core\StartUp\RegisterMiddleware;
use Mcms\Core\StartUp\RegisterRepositories;
use Mcms\Core\StartUp\RegisterServiceProviders;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Support\ServiceProvider;
use \Illuminate\Routing\Router;
use \Installer;

/**
 * Service Provider for the Core module
 * Class CoreServiceProvider
 * @package Mcms\Core
 */
class CoreServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        \Mcms\Core\Console\Commands\CreateUserRole::class,
        \Mcms\Core\Console\Commands\SeedUserRole::class,
        \Mcms\Core\Console\Commands\CreateUser::class,
        \Mcms\Core\Console\Commands\CreateUserPermissions::class,
        \Mcms\Core\Console\Commands\Install::class,
        \Mcms\Core\Console\Commands\InstallPackages::class,
        \Mcms\Core\Console\Commands\TranslationsImport::class,
        \Mcms\Core\Console\Commands\CreateAdmin::class,
        \Mcms\Core\Console\Commands\RefreshAssets::class,
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(Router $router, DispatcherContract $events, \Illuminate\Contracts\Http\Kernel $kernel)
    {
        $this->publishes([
            __DIR__.'/../config/core.php' => config_path('core.php'),
            __DIR__.'/../config/locales.php' => config_path('locales.php'),
            __DIR__.'/../config/laravellocalization.php' => config_path('laravellocalization.php'),
            __DIR__.'/../config/javascript.php' => config_path('javascript.php'),
            __DIR__.'/../config/widgets.php' => config_path('laravel-widgets.php'),
            __DIR__.'/../config/laravel-medialibrary.php' => config_path('laravel-medialibrary.php'),
            __DIR__.'/../config/debugbar.php' => config_path('debugbar.php'),
            __DIR__.'/../config/laratrust.php' => config_path('laratrust.php'),
            __DIR__.'/../config/user_profile.php' => config_path('user_profile.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../database/seeds/' => database_path('seeds')
        ], 'seeds');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/core-package'),
        ], 'views');


        if (!$this->app->routesAreCached()) {
            $router->group([
                'middleware' => 'web',
            ], function ($router) {
                require __DIR__.'/Http/routes.php';
            });

            $this->loadViewsFrom(__DIR__ . '/../resources/views', 'core');
        }

        /**
         * Register custom Blade directives
         */
        (new RegisterDirectives())->handle();
        /*
         * Register dependencies
        */
        (new RegisterServiceProviders())->handle();

        /*
         * Register middleware
         */
        (new RegisterMiddleware())->handle($this,$router, $kernel);

        /**
         * Register Events
         */
//        parent::boot($events);
        (new RegisterEvents())->handle($this, $events);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        /*
         * Register Commands
         */
        $this->commands($this->commands);

        /**
         * Register Facades
         */
        (new RegisterFacades())->handle($this);

        /**
         * Register Repositories
         */
        (new RegisterRepositories())->handle($this);

        /**
         * Register installer
         */
        Installer::register(\Mcms\Core\Installer\Install::class);
    }
}
