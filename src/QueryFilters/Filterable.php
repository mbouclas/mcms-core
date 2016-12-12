<?php

namespace Mcms\Core\QueryFilters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Attach it to the model you want to use query filters with
 * 
 * Class Filterable
 * @package Mcms\Core\QueryFilters
 */
trait Filterable
{
    /**
     * Filter a result set.
     *
     * @param  Builder      $query
     * @param  QueryFilters $filters
     * @return Builder
     */
    public function scopeFilter($query, QueryFilters $filters)
    {
        return $filters->apply($query);
    }
}