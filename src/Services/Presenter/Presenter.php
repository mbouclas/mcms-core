<?php

namespace Mcms\Core\Services\Presenter;

use Illuminate\Database\Eloquent\Model;

/**
 * Base presenter class. Extend it to create a presenter for your models
 * 
 * Class Presenter
 * @package Mcms\Core\Services\Presenter
 */

abstract class Presenter
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param $property
     * @return bool
     */
    public function __isset($property)
    {
        return method_exists($this, camel_case($property));
    }

    /**
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        $camel_property = camel_case($property);

        if (method_exists($this, $camel_property)) {
            return $this->{$camel_property}();
        }

        return $this->model->{snake_case($property)};
    }
}
