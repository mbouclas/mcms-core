<?php

namespace Mcms\Core\Services\Lang;


use Illuminate\Support\Collection;

class DeNormalizeOutput
{
    public function handle($normalizedInput)
    {
        $output = [];

        /**
         * Key and Group are common, so grab them from the first entry. Nothing fancy.
         */
        $key = $normalizedInput[0]->key;
        $group = $normalizedInput[0]->group;
        $output['key'] = $key;
        $output['group'] = $group;

        foreach ($normalizedInput as $item) {
            $output[$item->locale] = $item;
        }

        return $output;
    }
}