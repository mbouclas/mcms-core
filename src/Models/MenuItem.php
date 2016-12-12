<?php

namespace Mcms\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Themsaid\Multilingual\Translatable;

class MenuItem extends Model
{
    use Translatable, NodeTrait;
    protected $table = 'menu_items';
    public $translatable = ['title'];
    public $casts = [
        'title' => 'array',
        'settings' => 'array',
        'sync' => 'boolean',
        'active' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'menu_id',
        'item_id',
        'name',
        'link',
        'title',
        'permalink',
        'model',
        'slug_pattern',
        'settings',
        'orderBy',
        'active',
        'sync'
    ];


    /**
     * Allows for :
     * MenuItem::scoped([ 'menu_id' => 5 ])->get(); // only for this menuId
     * 
     * @return array
     */
    protected function getScopeAttributes()
    {
        return [ 'menu_id' ];
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

}
