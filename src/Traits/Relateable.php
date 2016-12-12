<?php

namespace Mcms\Core\Traits;


use Illuminate\Support\Collection;

trait Relateable
{
    public function saveRelated(array $array)
    {

        //get the ids
        $queries = [
//            'item_id' => null,
            'source_item_id' => null,
            'model' => null,
        ];

        $model = new $this->relatedModel;

        //drop old ones
        $model->where('source_item_id', $this->id)
            ->where('model', get_class($this))
            ->delete();

        foreach ($array as $index => $item) {
//            $queries['item_id'][] = $item['item_id'];
            $queries['source_item_id'] = $item['source_item_id'];
            $queries['model'] = $item['model'];
        }

        foreach ($queries as $key=> $value) {
            $model = $model->where($key, $value);
        }


        $model = $model = new $this->relatedModel;
        //add the new ones
        $items = [];
        foreach ($array as $index => $item) {
            unset($item['id']);//old ones
            $item['orderBy'] = $index;
            $items[] = $model->create($item);
        }

        return $items;
    }

    public function relatedItems()
    {
        if ( ! isset($this->related)){
            return $this;
        }

        $items = $this->related;
        $formattedItems = new Collection();
        $groups = [];
        $itemsById = [];
        foreach ($items as $index => $item){
            $groups[$item->dest_model][] = $item->item_id;
            $itemsById[$item->item_id] = $item;
        }

        foreach ($groups as $model => $group) {
            $items = (new $model())->whereIn('id', $group)->get();
             foreach ($items as $itemFound) {
                $itemsById[$itemFound->id]->item = $itemFound;
            }
        }
        $this->related = $itemsById;

        return $this;
    }
}