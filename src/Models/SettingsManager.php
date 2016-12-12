<?php

namespace Mcms\Core\Models;

use Illuminate\Database\Eloquent\Model;

class SettingsManager extends Model
{
    protected $table = 'settings_manager';
    protected $fillable = [
        'name', 'slug', 'item_id', 'model', 'fields', 'settings'
    ];

    protected $casts = [
        'fields' => 'array',
        'settings' => 'array',
    ];

    protected $dates = ['created_at', 'updated_at'];
}
