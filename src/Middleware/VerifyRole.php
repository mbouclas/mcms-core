<?php

namespace Mcms\Core\Middleware;


use Closure;
use Mcms\Core\Exceptions\RoleNotFoundException;
use Mcms\Core\Models\Role as RoleModel;
use Laratrust\Middleware\LaratrustRole;

class VerifyRole extends LaratrustRole
{
    public function handle($request, Closure $next, $roles)
    {
        //exact role

        if (!is_array($roles)) {
            $roles = explode(self::DELIMITER, $roles);
        }

        if ($this->auth->check() && $this->auth->user()->hasRole($roles)) {
            return $next($request);
        }

        $baseRole = RoleModel::where('name',$roles)->first();//get the info for the requested role (we need the level)
        if ( ! $baseRole){
            throw new RoleNotFoundException($roles);//role not found for some reason. Very bad
        }

        //grab the user roles in order to see if one of them is of higher level
        if ( ! method_exists($this->auth->user(), 'getRoles')){
            throw new RoleNotFoundException($roles);
        }

        $userRoles = $this->auth->user()->getRoles();
        foreach ($userRoles as $userRole){
            //This role is equal or higher level. Allow it
            if ($userRole->level >= $baseRole->level){
                return $next($request);
            }
        }

        /**
         *
         */
        if ($this->auth->guest() || !$request->user()->hasRole($roles)) {
            $message = ( ! $this->auth->user()) ?  'NoUser' : 'Forbidden';
            abort(403, $message);
        }

        return $next($request);
    }
}

