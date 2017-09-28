<?php

namespace  Mcms\Core\Models;

use Illuminate\Database\Eloquent\Model;


class GateKeeper extends Model
{

    protected $fillable = [
        'title',
        'gate',
        'level',
        'provider',
        'exceptions',
        'settings',
    ];

    public $casts = [
        'settings' => 'array',
        'exceptions' => 'array',
        'level' => 'level',
    ];

    protected $dates = ['created_at', 'updated_at'];
}
