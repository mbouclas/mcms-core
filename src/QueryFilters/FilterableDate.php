<?php

namespace Mcms\Core\QueryFilters;
use Carbon\Carbon;

trait FilterableDate
{
    /**
     * @param null|string $startDate
     * @return $this
     */
    public function dateStart($startDate = null)
    {
        $field = (isset($this->request->dateMode)) ? $this->request->dateMode : 'created_at';
        if ( ! $startDate){
            return $this->builder;
        }

        return $this->builder->where($field, '>=', Carbon::parse($startDate));
    }

    /**
     * @param null|string $endDate
     * @return $this
     */
    public function dateEnd($endDate = null)
    {
        $field = (isset($this->request->dateMode)) ? $this->request->dateMode : 'created_at';
        if ( ! $endDate){
            return $this->builder;
        }

        return $this->builder->where($field, '<=', Carbon::parse($endDate));
    }
}