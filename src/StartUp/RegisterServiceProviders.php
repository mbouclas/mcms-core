<?php

namespace Mcms\Core\StartUp;
use App;

/**
 * Register your dependencies Service Providers here
 * Class RegisterServiceProviders
 * @package Mcms\Core\StartUp
 */
class RegisterServiceProviders
{
    /**
     *
     */
    public function handle()
    {
        App::register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        App::register(\Barryvdh\Debugbar\ServiceProvider::class);
        App::register(\Cocur\Slugify\Bridge\Laravel\SlugifyServiceProvider::class);
        App::register(\Themsaid\Multilingual\MultilingualServiceProvider::class);
        App::register(\Arrilot\Widgets\ServiceProvider::class);
        App::register(\Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider::class);
        App::register(\Laracasts\Utilities\JavaScript\JavaScriptServiceProvider::class);
        App::register(\Barryvdh\TranslationManager\ManagerServiceProvider::class);
        App::register(\Conner\Tagging\Providers\TaggingServiceProvider::class);
        App::register(\Laratrust\LaratrustServiceProvider::class);
        App::register(\Conner\Likeable\LikeableServiceProvider::class);
        App::register(\Torann\GeoIP\GeoIPServiceProvider::class);

    }
}
