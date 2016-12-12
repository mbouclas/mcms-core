<?php

namespace Mcms\Core\Models;

use Illuminate\Database\Eloquent\Model;

abstract class Related extends Model
{
    protected $table = 'related';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'source_item_id',
        'item_id',
        'model',
        'dest_model',
        'orderBy',
        'settings',
        'dest_model'
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
