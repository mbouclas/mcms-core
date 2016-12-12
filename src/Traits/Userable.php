<?php
/**
 * Created by PhpStorm.
 * User: mbouclas
 * Date: 23/9/2016
 * Time: 10:58 πμ
 */

namespace Mcms\Core\Traits;


use Config;

trait Userable
{
    public function user()
    {
        return $this->belongsTo(Config::get('auth.providers.users.model'), 'user_id');
    }
}