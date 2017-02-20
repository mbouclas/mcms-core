<?php

namespace Mcms\Core\Models;


use Illuminate\Database\Eloquent\Model;

class DynamicTableItem extends Model
{
    protected $table = 'dynamic_tables_items';
    protected $fillable = [
        'item_id',
        'dynamic_table_id',
        'model',
    ];
    protected $dates = ['created_at', 'updated_at'];
}