<?php

namespace Mcms\Core\Facades;
use Illuminate\Support\Facades\Facade;

class ItemConnectorFacade extends Facade
{
    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ItemConnector'; // the IoC binding.
    }
}