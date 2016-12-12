<?php

namespace Mcms\Core\QueryFilters;


use Mcms\Core\Models\ExtraField;

/**
 * @example :
 * //$replace = ['extraFields' => [['slug' => 'test', 'value' => 1],['slug' => 'another-field', 'value' => 2],]];
$replace = ['extraFields' => 'another-field::2,test::1'];
$request->merge($replace);
$pages = \Mcms\Pages\Models\Page::filter($filters)
->select(['id'])
->get();
 *
 * Class FilterableExtraFields
 * @package Mcms\Core\QueryFilters
 */
trait FilterableExtraFields
{

    /**
     * @example:
     * ['extraFields' => [['slug' => 'test', 'value' => 1],['slug' => 'another-field', 'value' => 2],]]
     * OR
     * ['extraFields' => 'another-field::2,test::1']
     *
     * @param null $field
     * @return mixed
     */
    public function extraFields($field = null)
    {
        if ( ! $field){
            return $this->builder;
        }

        if (! is_array($field)) {
            $field = $field = explode(',',$field);
        }

        $builder = $this->builder;

        $slugs = [];
        $fieldsBySlug = [];
        foreach ($field as $item) {
            if ( ! is_array($item)){
                $item = explode('::', $item);
                if (count($item) !== 2){
                    continue;
                }
                $slugs[] = $item[0];
                $fieldsBySlug[$item[0]] = $item[1];
            } else {
                $slugs[] = $item['slug'];
                $fieldsBySlug[$item['slug']] = $item['value'];
            }
        }

        $fieldIds = ExtraField::whereIn('slug', $slugs)->select(['id' , 'slug'])->get()->toArray();

        foreach ($fieldIds as $found) {
            $builder = $builder->whereHas('extraFields', function($q) use ($found, $fieldsBySlug){
                $q->where('value', $fieldsBySlug[$found['slug']]);
                $q->where('extra_field_id', $found['id']);
            });
        }

        return $builder;
    }
}