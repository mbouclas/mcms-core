<?php


namespace Mcms\Core\Models;


/**
 * The point of this is to get Laravel convert JSON to utf8
 *
 * Class JsonToUtf8
 * @package Mcms\Core\Models
 */
trait JsonToUtf8
{
    /**
     * @param $value
     * @return string
     */
    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}