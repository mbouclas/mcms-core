<?php

namespace Mcms\Core\Traits;

use Mcms\Core\Exceptions\PresenterException;


/**
 * Adds presenter methods to your models. Don't forget to declare the $presenter property in your model.
 * 
 * Class Presentable
 * @package Mcms\Core\Traits
 */
trait Presentable
{

    /**
     * @var
     */
    protected $presenterInstance;

    /**
     * @return mixed
     * @throws PresenterException
     */
    public function present()
    {
        if (is_object($this->presenterInstance)) {
            return $this->presenterInstance;
        }

        if (property_exists($this, 'presenter') and class_exists($this->presenter)) {
            return $this->presenterInstance = new $this->presenter($this);
        }

        throw new PresenterException('Property $presenter was not set correctly in '.get_class($this));
    }
}
