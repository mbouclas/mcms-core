<?php

namespace Mcms\Core\Middleware;

use Closure;
use Laratrust\Middleware\LaratrustRole;
use Mcms\Core\Services\User\GateKeeper;

class VerifyGate extends LaratrustRole
{
    public function handle($request, Closure $next, $gate)
    {
        $gates = GateKeeper::gates($this->auth->user()->level())->pluck('gate')->toArray();
        if ($this->auth->check() && in_array($gate, $gates)) {
            return $next($request);
        }

        $message = ( ! $this->auth->user()) ?  'NoUser' : 'Forbidden';
        return abort(403, $message);
    }
}