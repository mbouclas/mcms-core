<?php

namespace Mcms\Core\Models\QueryScopes;


class CommonQueryScopes
{
    public static function active($active = true)
    {
        return function ($query) use ($active) {
            $query->where('active', $active);
        };
    }

    public static function dynamic($key, $value)
    {
        return function ($query) use ($key, $value) {
            $query->where($key, $value);
        };
    }

    public static function sort($order, $direction)
    {
        return function ($query) use ($order, $direction) {
            $query->orderBy($order, $direction);
        };
    }

    public static function limit($limit)
    {
        return function ($query) use ($limit) {
            $query->take($limit);
        };
    }
}