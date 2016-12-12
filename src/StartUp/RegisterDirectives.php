<?php

namespace Mcms\Core\StartUp;


use Mcms\Core\Widgets\Widget;

/**
 * Register all your Blade directives here
 * Class RegisterDirectives
 * @package Mcms\Core\StartUp
 */
class RegisterDirectives
{
    /**
     *
     */
    public function handle()
    {
        Widget::registerDirective();//will register the @Widget directive
        Widget::registerWidgetCompiler();//will register the @Compose directive
    }
}