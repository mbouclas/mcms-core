<?php

namespace Mcms\Core\Widgets;

use Illuminate\Support\Facades\Facade;

class WidgetFacade extends Facade
{
    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Widget'; // the IoC binding.
    }

}