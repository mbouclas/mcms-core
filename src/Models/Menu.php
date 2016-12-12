<?php

namespace Mcms\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

    protected $table = 'menus';
    public $casts = ['settings' => 'array'];

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
        'title',
        'description',
        'slug',
        'settings'
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }
 


}
