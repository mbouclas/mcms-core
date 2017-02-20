<?php

namespace Mcms\Core\QueryFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * This creates DB filters from a query string
 * Extend it per model. Don't forget to add the trait to the model
 * Class QueryFilters
 * @package Mcms\Core\QueryFilters
 */
abstract class QueryFilters
{
    /**
     * The request object.
     *
     * @var Request
     */
    public $request;
    /**
     * The builder instance.
     *
     * @var Builder
     */
    protected $builder;

    public $model;

    /**
     * Define your filterable methods
     * @var array
     */
    protected $filterable = [];
    /**
     * Create a new QueryFilters instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Apply the filters to the builder.
     *
     * @param  Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->filters() as $name => $value) {
            if (!method_exists($this, $name)) {
                continue;
            }
            
            if ($this->request->has($name)) {
                $this->$name($value);
            } else {
                $this->$name();
            }
        }

        return $this->builder;
    }
    /**
     * Get all request filters data.
     *
     * @return array
     */
    public function filters()
    {
        return $this->request->only($this->filterable);
    }
}