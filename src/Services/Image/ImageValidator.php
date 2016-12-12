<?php


namespace Mcms\Core\Services\Image;
use Mcms\Core\Exceptions\InvalidImageFormatException;
use Validator;

class ImageValidator
{
    public function validate(array $item)
    {
        $check = Validator::make($item, [
            'item_id' => 'required',
            'user_id' => 'required',
            'model' => 'required',
            'type' => 'required',
            'alt' => 'array',
            'title' => 'array',
            'copies' => 'required|array',
        ]);

        if ($check->fails()) {
            throw new InvalidImageFormatException($check->errors());
        }

        return true;
    }
}