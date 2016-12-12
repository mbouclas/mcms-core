<?php
/**
 * Created by PhpStorm.
 * User: mbouc
 * Date: 22-Jun-16
 * Time: 13:02 PM
 */

namespace Mcms\Core\Services\Image;


use Illuminate\Support\Collection;

class GroupImagesByType
{
    public function group(Collection $images, array $imageCategories)
    {
        $groups = [];

        foreach ($imageCategories as $category){
            $groups[$category['name']] = [];
        }

        $images = $images
            ->groupBy('type')
            ->sortBy('orderBy');

        foreach ($groups as $key => $group){
            if (isset($images[$key])){
                $groups[$key] = $images[$key];
            }
        }


        return $this->sort($groups);
    }

    public function sort($images)
    {
        foreach ($images as $key => $group){
            //using array_values to reset the array keys, as sortBy sorts the array
            //but messes up the keys
            if ( ! empty($group)){
                $images[$key] = array_values($group->sortBy('orderBy')->toArray());
            }
        }

        return $images;
    }
}