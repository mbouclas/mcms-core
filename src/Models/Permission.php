<?php

namespace Mcms\Core\Models;


use Laratrust\LaratrustPermission;

class Permission extends LaratrustPermission
{
    protected $fillable = ['display_name', 'name', 'description'];
}