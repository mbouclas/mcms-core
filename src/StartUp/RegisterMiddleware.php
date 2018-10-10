<?php

namespace Mcms\Core\StartUp;


use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Mcms\Core\Http\Middleware\JwtMiddleware;
use Mcms\Core\Middleware\Cors;

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
     * @param Kernel $kernel
     */
    public function handle(ServiceProvider $serviceProvider, Router $router, Kernel $kernel)
    {
        // Enable CORS
        $kernel->pushMiddleware(Cors::class);

//        $router->middleware('role', \Mcms\Core\Middleware\Role::class);
//        $router->middleware('permission', \Bican\Roles\Middleware\VerifyPermission::class);
//        $router->middleware('level', \Bican\Roles\Middleware\VerifyLevel::class);

//        $router->middleware('role', \Laratrust\Middleware\LaratrustRole::class);
        $router->aliasMiddleware('role', \Mcms\Core\Middleware\VerifyRole::class);
        $router->aliasMiddleware('permission', \Laratrust\Middleware\LaratrustPermission::class);
        $router->aliasMiddleware('level', \Mcms\Core\Middleware\VerifyLevel::class);
        $router->aliasMiddleware('gate', \Mcms\Core\Middleware\VerifyGate::class);
        $router->aliasMiddleware('CORS', Cors::class);
        $router->aliasMiddleware('jwt.verify', JwtMiddleware::class);
//        $router->aliasMiddleware('jwt.auth', \Tymon\JWTAuth\Middleware\GetUserFromToken::class);
//        $router->aliasMiddleware('jwt.refresh', \Tymon\JWTAuth\Middleware\RefreshToken::class);

    }
}