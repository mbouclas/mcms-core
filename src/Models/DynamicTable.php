<?php

namespace Mcms\Core\Models;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Mcms\Core\Traits\Presentable;
use Mcms\Core\Traits\Userable;
use Themsaid\Multilingual\Translatable;

class DynamicTable extends Model
{
    use Translatable, NodeTrait, Presentable, Userable;

    protected $table = 'dynamic_tables';
    public $translatable = ['title', 'description'];

    protected $fillable = [
        'title',
        'description',
        'model',
        'slug',
        'user_id',
        'settings',
        'meta',
        'active',
        'thumb',
        'orderBy',
        'table_id'
    ];

    public $casts = [
        'title' => 'array',
        'description' => 'array',
        'settings' => 'array',
        'meta' => 'array',
        'thumb' => 'array',
        'active' => 'boolean'
    ];

    protected $dates = ['created_at', 'updated_at'];

    protected function getScopeAttributes()
    {
        return [ 'table_id' ];
    }

    protected $defaultRoute = 'dynamic-tables';
    public $config;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

}