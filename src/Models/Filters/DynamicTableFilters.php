<?php

namespace Mcms\Core\Models\Filters;


use Mcms\Core\Models\DynamicTableItem;

trait DynamicTableFilters
{
    public function dt($dt = null)
    {
        if ( ! $dt){
            return $this->builder;
        }

        if (! is_array($dt)) {
            $dt = $dt = explode(',',$dt);
        }

        $ids = [];
        $slugs = [];
        foreach ($dt as $index => $value) {
            if ( ! is_numeric($value)) {
                $slugs[] = $value;
                continue;
            }

            $ids[] = $value;
        }


        return $this->builder->whereHas('dynamicTables', function ($q) use ($ids, $slugs){
            if (count($ids) > 0) {
                $q->whereIn('dynamic_table_id', $ids);
            }

            if (count($slugs) > 0) {
                $q->whereRaw('dynamic_table_id IN (
                SELECT id FROM dynamic_tables WHERE slug 
                      IN ("' . implode('", "', $slugs) . '") 
                      AND dynamic_tables.model = ?
                )', [$this->model]);
            }
        });
    }
}