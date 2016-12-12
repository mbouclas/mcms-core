<?php

namespace Mcms\Core\StartUp;


use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

/**
 * Class RegisterMiddleware
 * @package Mcms\Core\StartUp
 */
class RegisterMiddleware
{

    /**
     * Register all your middleware here
     * @param ServiceProvider $serviceProvider
     * @param Router $router
     */
    public function handle(ServiceProvider $serviceProvider, Router $router)
    {
//        $router->middleware('role', \Mcms\Core\Middleware\Role::class);
//        $router->middleware('permission', \Bican\Roles\Middleware\VerifyPermission::class);
//        $router->middleware('level', \Bican\Roles\Middleware\VerifyLevel::class);

//        $router->middleware('role', \Laratrust\Middleware\LaratrustRole::class);
        $router->middleware('role', \Mcms\Core\Middleware\VerifyRole::class);
        $router->middleware('permission', \Laratrust\Middleware\LaratrustPermission::class);
        $router->middleware('level', \Mcms\Core\Middleware\VerifyLevel::class);
    }
}