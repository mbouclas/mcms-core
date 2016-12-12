<?php

namespace Mcms\Core\Models\Filters;


use Mcms\Core\QueryFilters\FilterableDate;
use Mcms\Core\QueryFilters\FilterableOrderBy;
use Mcms\Core\QueryFilters\QueryFilters;

/**
 * Class TranslationFilters
 * @package Mcms\Core\Models\Filters
 */
class TranslationFilters extends QueryFilters
{
    use FilterableDate, FilterableOrderBy;
    /**
     * @var array
     */
    protected $filterable = [
        'key',
        'group',
        'value',
        'status',
        'dateStart',
        'dateEnd',
        'orderBy',
    ];

    /**
     * @example ?status=active,inactive O
     * @param null|string $status
     * @return mixed
     */
    public function status($status = null)
    {
        if ( ! $status){
            return $this->builder;
        }
        
        //In case ?status=active,inactive
        if (! is_array($status)) {
            $status = $status = explode(',',$status);
        }

        return $this->builder->whereIn('status', $status);
    }

    /**
     * @param null|string $key
     * @return $this
     */
    public function key($key = null)
    {
        if ( ! $key){
            return $this->builder;
        }

        return $this->builder->where('key', 'LIKE', "%{$key}%");
    }

    /**
     * @param null|string $value
     * @return $this
     */
    public function value($value = null)
    {
        if ( ! $value){
            return $this->builder;
        }

        return $this->builder->where('value', 'LIKE', "%{$value}%");
    }

    /**
     * @param null|string $group
     * @return mixed
     */
    public function group($group = null)
    {
        if ( ! $group){
            return $this->builder;
        }

        if (! is_array($group)) {
            $group = $group = explode(',',$group);
        }

        return $this->builder->where('group', $group);
    }
}