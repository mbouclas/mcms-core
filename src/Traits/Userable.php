<?php
namespace Mcms\Core\Traits;


use Config;

trait Userable
{
    public function user()
    {
        return $this->belongsTo(Config::get('auth.providers.users.model'), 'user_id');
    }
}