<?php

namespace Mcms\Core\Models;

use Barryvdh\TranslationManager\Models\Translation as BaseTranslation;
use Mcms\Core\QueryFilters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Themsaid\Multilingual\Translatable;

class Translation extends BaseTranslation
{
    use Filterable;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
}
