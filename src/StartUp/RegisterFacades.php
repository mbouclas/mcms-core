<?php

namespace Mcms\Core\StartUp;

use Mcms\Core\Facades\ItemConnectorFacade;
use Mcms\Core\Services\Installer\Install;
use Mcms\Core\Services\Menu\ConnectorRegistry;
use Mcms\Core\Widgets\WidgetFacade;
use Mcms\Core\Widgets\Widget;
use App;
use Illuminate\Support\ServiceProvider;

/**
 * Register your Facades/aliases here
 * Class RegisterFacades
 * @package Mcms\Core\StartUp
 */
class RegisterFacades
{
    /**
     * @param ServiceProvider $serviceProvider
     */
    public function handle(ServiceProvider $serviceProvider)
    {
        //Instantiate the Installer facade
        App::bind('Installer', function(){
            return new Install();
        });

        //Instantiate the Widget facade
        App::bind('Widget', function(){
            return new Widget();
        });

        App::bind('ItemConnector', function(){
            return new ConnectorRegistry();
        });
        
        $facades = \Illuminate\Foundation\AliasLoader::getInstance();
        $facades->alias('Debugbar', \Barryvdh\Debugbar\Facade::class);
        $facades->alias('Installer', \Mcms\Core\Facades\InstallerFacade::class);
        $facades->alias('Widget', WidgetFacade::class);
        $facades->alias('ItemConnector', ItemConnectorFacade::class);
        $facades->alias('LaravelLocalization', \Mcamara\LaravelLocalization\Facades\LaravelLocalization::class);
        $facades->alias('Laratrust', \Laratrust\LaratrustFacade::class);
        $facades->alias('GeoIP', \Torann\GeoIP\Facades\GeoIP::class);
    }
}