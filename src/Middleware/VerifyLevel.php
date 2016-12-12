<?php

namespace Mcms\Core\Middleware;


use Closure;
use Mcms\Core\Exceptions\RoleNotFoundException;
use Mcms\Core\Models\Role as RoleModel;
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

