<?php

namespace Mcms\Core\Traits;


trait Featurable
{
    public function saveFeatured(array $array)
    {
        //get the ids
        $queries = [
            'item_id' => [],
            'category_id' => [],
            'model' => [],
        ];

        $model = new $this->featuredModel;

        foreach ($array as $index => $item) {
            $queries['item_id'][] = $item['item_id'];
            $queries['category_id'][] = $item['category_id'];
            $queries['model'][] = $item['model'];
        }

        foreach ($queries as $key=> $value) {
            $model = $model->whereIn($key, $value);
        }


        //drop old ones
        $model->delete();

        $model = $model = new $this->featuredModel;
        //add the new ones
        $items = [];
        foreach ($array as $index => $item) {
            unset($item['id']);//old ones
            $item['orderBy'] = $index;
            $items[] = $model->create($item);
        }

        return $items;
    }
}