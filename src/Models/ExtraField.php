<?php

namespace Mcms\Core\Models;

use Mcms\Core\QueryFilters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Mcms\FrontEnd\Helpers\Sluggable;
use Themsaid\Multilingual\Translatable;

class ExtraField extends Model
{
    use Translatable, Filterable, Sluggable;

    public $translatable = ['title'];
    public $casts = [
        'label' => 'array',
        'settings' => 'array',
        'meta' => 'array',
        'active' => 'boolean'
    ];
    protected $table = 'extra_fields';
    protected $fillable = [
        'label',
        'model',
        'varName',
        'slug',
        'type',
        'meta',
        'settings',
        'active',
        'orderBy',
    ];
    protected $dates = ['created_at', 'updated_at'];

    public function values()
    {
//        return $this->hasMany()
    }
}
