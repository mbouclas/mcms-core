<?php

namespace Mcms\Core\Models;

use Config;
use Mcms\Core\Models\ExtraFieldValue as BaseExtraFieldValue;


/**
 * Class Page
 * @package Mcms\Pages\Models
 */
class UserExtraFieldValue extends BaseExtraFieldValue
{
    protected $userModel;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->userModel = (Config::has('auth.providers.users.model')) ? Config::get('auth.providers.users.model') : User::class;
    }

    public function field()
    {
        return $this->BelongsTo(ExtraField::class, 'extra_field_id');
    }

}
