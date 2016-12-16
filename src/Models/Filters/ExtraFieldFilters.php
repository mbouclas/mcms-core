<?php

namespace Mcms\Core\Models\Filters;


use App;
use Mcms\Core\QueryFilters\FilterableDate;
use Mcms\Core\QueryFilters\FilterableOrderBy;
use Mcms\Core\QueryFilters\QueryFilters;

/**
 * Class ExtraFieldFilters
 * @package Mcms\Core\Models\Filters
 */
class ExtraFieldFilters extends QueryFilters
{
    use FilterableDate, FilterableOrderBy;
    /**
     * @var array
     */
    protected $filterable = [
        'id',
        'label',
        'model',
        'varName',
        'slug',
        'type',
        'active',
        'dateStart',
        'dateEnd',
        'orderBy',
    ];

    public function id($id = null)
    {
        if ( ! isset($id)){
            return $this->builder;
        }


        if (! is_array($id)) {
            $id = $id = explode(',',$id);
        }

        return $this->builder->whereIn('id', $id);
    }

    public function label($label = null)
    {
        $locale = App::getLocale();
        if ( ! $label){
            return $this->builder;
        }

        $label = mb_strtolower($label);
        return $this->builder->whereRaw(\DB::raw("LOWER(`label`->'$.\"{$locale}\"') LIKE '%{$label}%'"));
    }

    public function to($to = null)
    {
        if ( ! $to){
            return $this->builder;
        }

        //In case ?to=active,inactive
        if (! is_array($to)) {
            $to = $to = explode(',',$to);
        }

        return $this->builder->whereIn('to', $to);
    }

    /**
     * @param null|string $model
     * @return $this
     */
    public function model($model = null)
    {
        if ( ! $model){
            return $this->builder;
        }

        return $this->builder->where('model', 'LIKE', "%{$model}%");
    }

    /**
     * @param null|string $varName
     * @return $this
     */
    public function varName($varName = null)
    {
        if ( ! $varName){
            return $this->builder;
        }

        return $this->builder->where('varName', 'LIKE', "%{$varName}%");
    }

    /**
     * @param null|string $slug
     * @return $this
     */
    public function slug($slug = null)
    {
        if ( ! $slug){
            return $this->builder;
        }

        return $this->builder->where('slug', 'LIKE', "%{$slug}%");
    }

    public function active($active = null)
    {
        if ( ! isset($active)){
            return $this->builder;
        }

        //In case ?status=active,inactive
        if (! is_array($active)) {
            $active = $active = explode(',',$active);
        }

        return $this->builder->whereIn('active', $active);
    }
}