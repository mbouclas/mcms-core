<?php

namespace Mcms\Core\Exceptions;


class RoleNotFoundException extends  \Exception
{
    public function __construct($role)
    {
        $this->message = 'This is not a valid role';
    }
}