<?php

namespace Mcms\Core\Facades;
use Illuminate\Support\Facades\Facade;

class InstallerFacade extends Facade
{
    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Installer'; // the IoC binding.
    }
}