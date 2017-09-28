<?php

namespace Mcms\Core\Middleware;


use Closure;
use Laratrust\Middleware\LaratrustRole;

class VerifyLevel extends LaratrustRole
{
    public function handle($request, Closure $next, $level)
    {
        if ($this->auth->check() && $this->auth->user()->level() >= $level) {
            return $next($request);
        }

        $message = ( ! $this->auth->user()) ?  'NoUser' : 'Forbidden';
        return abort(403, $message);
    }
}

