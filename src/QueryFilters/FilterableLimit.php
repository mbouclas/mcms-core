<?php

namespace Mcms\Core\QueryFilters;


trait FilterableLimit
{
    /**
     * @param null|string $limit
     * @return $this
     */
    public function limit($limit = null)
    {

        if ( ! $limit){
            return $this;
        }

        return $this->builder
            ->take($limit);

    }
}