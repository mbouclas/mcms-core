<?php

namespace Mcms\Core\QueryFilters;


trait FilterableOrderBy
{
    /**
     * @param null|string $orderBy
     * @return $this
     */
    public function orderBy($orderBy = null)
    {

        if ( ! $orderBy){
            $orderBy = 'created_at';
        }
        $way = ($this->request->has('way'))? $this->request->input('way') : 'DESC';

        return $this->builder
            ->orderBy($orderBy,$way);

    }
}