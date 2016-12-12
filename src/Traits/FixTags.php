<?php

namespace Mcms\Core\Traits;


trait FixTags
{
    public function fixTags($array, $Model){
        if (isset($array['tagged'])){
            if (is_array($array['tagged'])){
                $newTags = [];
                foreach ($array['tagged'] as $tag){
                    $newTags[] = $tag['name'];
                }

                $Model->retag($newTags);
            }
        }

        return $Model;
    }
}