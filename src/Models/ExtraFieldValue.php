<?php

namespace Mcms\Core\Models;

use Mcms\Core\QueryFilters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Mcms\FrontEnd\Helpers\Sluggable;
use Themsaid\Multilingual\Translatable;

abstract class ExtraFieldValue extends Model
{
    use Translatable;

    public $translatable = ['value'];
    public $casts = [
//        'value' => 'array',
    ];
    protected $table = 'extra_field_values';
    protected $fillable = [
        'item_id',
        'model',
        'extra_field_id',
        'value',
    ];
    protected $dates = ['created_at', 'updated_at'];

    public function values()
    {
//        return $this->hasMany()
    }
}
