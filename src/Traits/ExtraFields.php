<?php

namespace Mcms\Core\Traits;


use Mcms\Core\Models\ExtraField;
use Illuminate\Support\Collection;

trait ExtraFields
{
    public function extraFieldValues()
    {
        return $this->belongsToMany(ExtraField::class, 'extra_field_values', 'item_id', 'extra_field_id')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function sortOutExtraFields(array $fields)
    {
        if (empty($fields) || count($fields) == 0) {
            return [];
        }

        //our fields are in a key-value state, we need to form them properly getting the missing info
        $model = get_class($this);
        $Collection = new Collection();
        foreach ($fields as $key => $value){
            $Collection->push([
                'varName' => $key,
                'value' => $value
            ]);
        }
        $allFields = $Collection->pluck('varName');
        $found = ExtraField::whereIn('varName', $allFields->toArray())
            ->where('model', $model)
            ->select(['id', 'varName'])
            ->get();

        foreach ($Collection as $index => $field) {
            $field['extra_field_id'] = $found->where('varName', $field['varName'])->first()->id;
            $field['model'] = $model;
            $field['item_id'] = $this->id;
            $field['value'] = (is_array($field['value'])) ? json_encode($field['value']) : $field['value'];
            unset($field['varName']);
            $Collection[$index] = $field;
        }

        return $Collection->toArray();
    }


}