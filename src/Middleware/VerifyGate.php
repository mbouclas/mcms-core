<?php

namespace Mcms\Core\Middleware;

use Closure;
use Laratrust\Middleware\LaratrustRole;
use Mcms\Core\Services\User\GateKeeper;

class VerifyGate extends LaratrustRole
{
    public function handle($request, Closure $next, $input)
    {
        $params = explode('|', $input);
        $gates = GateKeeper::allGates();
        $userGates = $gates->where('level', '<=', $this->auth->user()->level());
        $gate = $gates->where('gate', $params[0])->first();
        // If there's an exception to this gate, allow it. Maybe the owner is allowed even if the gate disallows him
        if (is_array($gate->exceptions) && in_array('owner', $gate->exceptions)) {
            return $next($request);
        }

        if ($this->auth->check() && in_array($params[0], $userGates->pluck('gate')->toArray())) {
            return $next($request);
        }

        $message = (!$this->auth->user()) ? 'NoUser' : 'Forbidden';
        return abort(403, $message);
    }
}