<?php

namespace Mcms\Core\QueryFilters;


trait FilterableTagged
{
    public function tag($tag = null)
    {
        if ( ! $tag){
            return $this->builder;
        }

        if (! is_array($tag)) {
            $tag = $tag = explode(',',$tag);
        }

        return $this->builder->whereHas('tagged', function ($q) use ($tag){
            $q->whereIn('tag_slug', $tag);
        });
    }
}