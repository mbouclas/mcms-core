<?php

namespace Mcms\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Themsaid\Multilingual\Translatable;

class FileGallery extends Model
{
    use Translatable;
    protected $table = 'file_gallery';
    public $translatable = ['title', 'description'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'model',
        'file_name',
        'url',
        'title',
        'description',
        'info',
        'user_id',
        'settings',
        'active'
    ];

    public $casts = [
        'title' => 'array',
        'description' => 'array',
        'settings' => 'array',
        'info' => 'array',
        'active' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];


}
