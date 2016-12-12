<?php

namespace Mcms\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Themsaid\Multilingual\Translatable;

class Image extends Model
{
    use Translatable;
    protected $table = 'images';
    public $translatable = ['alt', 'title', 'description'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'model',
        'type',
        'title',
        'description',
        'alt',
        'info',
        'user_id',
        'copies',
        'settings',
        'active'
    ];

    public $casts = [
        'title' => 'array',
        'alt' => 'array',
        'description' => 'array',
        'copies' => 'array',
        'settings' => 'array',
        'active' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];


}
