<?php

namespace Mcms\Core\Models\Filters;

use Auth;
use Mcms\Admin\GateKeeper\GateKeeper;
use Mcms\Core\Models\Role;
use Mcms\Core\QueryFilters\FilterableDate;
use Mcms\Core\QueryFilters\FilterableLimit;
use Mcms\Core\QueryFilters\FilterableOrderBy;
use Mcms\Core\QueryFilters\QueryFilters;


/**
 * Implement query based filtering for the User model
 * Class UserFilters
 * @package Mcms\Core\Models\Filters
 */
class UserFilters extends QueryFilters
{
    use FilterableDate, FilterableOrderBy, FilterableLimit;
    /**
     * @var array
     */
    protected $filterable = [
        'id',
        'active',
        'email',
        'name',
        'role',
        'dateStart',
        'dateEnd',
        'orderBy',
        'role',
        'permission',
        'awaits_moderation',
        'roleHigherThan',
        'roleLowerThan',
        'gate'
    ];

    /**
     * @example ?active=active,inactive O
     * @param $active
     * @return mixed
     */
    public function active($active = null)
    {
        if (is_string($active)) {
            $active = ($active == 'true') ? 1 : false;
        }

        if ( is_null($active)){
            return $this->builder;
        }

        //In case ?active=active,inactive
        if (! is_array($active)) {
            $active = $active = explode(',',$active);
        }

        return $this->builder->whereIn('active', $active);
    }


    public function email($email = null)
    {
        if ( ! $email){
            return $this->builder;
        }

        return $this->builder->where('email', 'LIKE', "%{$email}%");
    }

    public function name($name = null)
    {
        if ( ! $name){
            return $this->builder;
        }
        $parts = explode(' ', $name);
        if (count($parts) > 1){
            return $this->builder->where(function($query) use ($parts) {
                $query->orWhere('firstName','LIKE' , "%{$parts[0]}%");
                $query->orWhere('lastName','LIKE' , "%{$parts[1]}%");
            });
        }

        return $this->builder->where(function($query) use ($name) {
            $query->orWhere('firstName','LIKE' , "%{$name}%");
            $query->orWhere('lastName','LIKE' , "%{$name}%");
        });
    }


    public function id($id = null)
    {
        if ( ! $id){
            return $this->builder;
        }

        //join first
        //In case ?role=guest,admin,moderator
        if (! is_array($id)) {
            $id = $id = explode(',',$id);
        }

        return $this->builder->whereIn('id', $id);
    }

    public function roleHigherThan($role = null)
    {
        if ( ! $role){
            return $this->builder;
        }

        $role = Role::where('name', $role)->first();
        if (! $role) {
            return $this->builder;
        }

        $roles = Role::where('level','>=',$role->level)->get();

        return $this->builder->whereHas('roles', function ($q) use ($roles) {
            $q->whereIn('name', $roles->pluck('name'));
        });
    }

    public function roleLowerThan($role = null)
    {
        if ( ! $role){
            return $this->builder;
        }

        $role = Role::where('name', $role)->first();
        if (! $role) {
            return $this->builder;
        }

        $roles = Role::where('level','<=',$role->level)->get();

        return $this->builder->whereHas('roles', function ($q) use ($roles) {
            $q->whereIn('name', $roles->pluck('name'));
        });
    }

    public function role($role = null)
    {
        if ( ! $role){
            return $this->builder;
        }

        //join first
        //In case ?role=guest,admin,moderator
        if (! is_array($role)) {
            $role = $role = explode(',',$role);
        }

        return $this->builder->whereHas('roles', function ($q) use ($role) {
            $q->whereIn('name', $role);
        });
    }

    public function gate($gate = null)
    {
        if (is_null($gate)){
            return $this->builder;
        }

        $gate = \Mcms\Core\Models\GateKeeper::where('gate', $gate)->first();
        if (! $gate){
            // make it fail
            return $this->id('failed');
        }

        $roles = Role::where('level','>=',$gate->level)->get();

        return $this->role(implode(',', $roles->pluck('name')->toArray()));
    }

    public function permission($permission = null)
    {
        if ( ! $permission){
            return $this->builder;
        }

        //join first
        //In case ?permission=guest,admin,moderator
        if (! is_array($permission)) {
            $permission = $permission = explode(',',$permission);
        }

        return $this->builder->whereHas('permissions', function ($q) use ($permission) {
            $q->whereIn('name', $permission);
        });
    }

    public function level($level = null)
    {
        if ( ! $level){
            return $this->builder;
        }

        //join first
        //In case ?level=guest,admin,moderator
        if (! is_array($level)) {
            $level = $level = explode(',',$level);
        }

        return $this->builder->whereHas('roles', function ($q) use ($level) {
            $q->whereIn('level', $level);
        });
    }

    public function awaits_moderation($awaits_moderation = null)
    {
        if ( ! $awaits_moderation){
            return $this->builder;
        }

        //In case ?awaits_moderation=active,inactive
        if (! is_array($awaits_moderation)) {
            $awaits_moderation = $awaits_moderation = explode(',',$awaits_moderation);
        }

        return $this->builder->whereIn('awaits_moderation', $awaits_moderation);
    }
}