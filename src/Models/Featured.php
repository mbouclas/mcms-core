<?php

namespace Mcms\Core\Models;

use Illuminate\Database\Eloquent\Model;

abstract class Featured extends Model
{
    protected $table = 'featured';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'category_id',
        'model',
        'orderBy',
        'settings'
    ];

    public $casts = [
        'settings' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];



}
