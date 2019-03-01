<?php

namespace Mcms\Core\ExtraFields;

    /*    $page = \Mcms\Pages\Models\Page::with(['extraFields', 'extraFields.field'])->find(103);
        print_r($page->toArray());*/

    /*    $replace = ['extraFields' => [
            ['slug' => 'test', 'value' => 1],
            ['slug' => 'another-field', 'value' => 2],
        ]];*/
    /*    $replace = ['extraFields' => 'another-field::2,test::1'];
        $request->merge($replace);
        $pages = \Mcms\Pages\Models\Page::filter($filters)
            ->select(['id'])
            ->get();

        print_r($pages->toArray());*/

use Mcms\Core\Models\ExtraField;

class ExtraFields
{
    public $model;

    public function __construct()
    {
        $this->model = new ExtraField();
    }

    public function update($id, array $item)
    {
        $item['model'] = str_replace('\\\\', '\\', $item['model']);
        $item['slug'] = str_slug($item['varName']);
        $Item = $this->model->find($id);
        $Item->update($item);

        event('extraField.updated', $Item);
        return $Item;
    }

    public function store(array $item)
    {
        $item['model'] = str_replace('\\\\', '\\', $item['model']);
        $item['slug'] = str_slug($item['varName']);
        $Item = $this->model->create($item);

        event('extraField.created', $Item);
        return $Item;
    }

    public function destroy($id)
    {
        $item = $this->model->find($id);

        event('extraField.destroyed', $item);
        return $item->delete();
    }
}