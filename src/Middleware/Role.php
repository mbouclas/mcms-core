<?php

namespace Mcms\Core\Middleware;


use Bican\Roles\Exceptions\RoleDeniedException;
use Bican\Roles\Middleware\VerifyRole;
use Bican\Roles\Models\Role as RoleModel;
use Closure;
use Mcms\Core\Exceptions\RoleNotFoundException;
use Symfony\Component\Debug\Exception\FatalThrowableError;

/**
 * This middleware will check if the user either has an exact role OR a role higher in the chain than
 * the one required. SU is allowed to do the same stuff an admin can.
 *
 * Class Role
 * @package Mcms\Core\Middleware
 */
class Role extends VerifyRole
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param int|string $role
     * @return mixed
     * @throws RoleDeniedException
     * @throws RoleNotFoundException
     */
    public function handle($request, Closure $next, $role)
    {
        //exact role
        if ($this->auth->check() && $this->auth->user()->is($role)) {
            return $next($request);
        }

        //look up for higher level match
        $baseRole = RoleModel::where('slug',$role)->first();//get the info for the requested role (we need the level)
        if ( ! $baseRole){
            throw new RoleNotFoundException($role);//role not found for some reason. Very bad
        }

        //grab the user roles in order to see if one of them is of higher level
        if ( ! method_exists($this->auth->user(), 'getRoles')){
            throw new RoleNotFoundException($role);
        }

        $userRoles = $this->auth->user()->getRoles();
        foreach ($userRoles as $userRole){
            //This role is equal or higher level. Allow it
            if ($userRole->level >= $baseRole->level){
                return $next($request);
            }
        }

        //nothing found, throw an exception
        throw new RoleDeniedException($role);
    }
}