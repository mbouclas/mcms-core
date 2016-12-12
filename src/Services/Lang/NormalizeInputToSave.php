<?php

namespace Mcms\Core\Services\Lang;

use Mcms\Core\Services\Lang\Contracts\LanguagesContract;

/**
 * Normalizes a json input to the form our Model implementation is expecting
 *
 * Class NormalizeInput
 * @package Mcms\Core\Services\Lang
 */
class NormalizeInputToSave
{
    /**
     * @var array
     */
    protected $locales;

    /**
     * NormalizeInput constructor.
     * @param array $locales
     */
    public function __construct(array $locales)
    {
        $this->locales = $locales;
    }
    /**
     * What we need to do, is figure out the locales available in the input
     * Then break it down to rows
     * Finally return an array of rows to be inserted in the DB
     * 
     * @param array $input
     * @return array
     */
    public function handle($input)
    {
        $normalizedInput = [];

        foreach ($this->locales as $key => $locale) {
            if ( ! array_key_exists($key, $input)){
                continue;
            }
            
            $input[$key]['key'] = $input['key'];
            $input[$key]['group'] = $input['group'];
            $normalizedInput[] = $input[$key];
        }

        return $normalizedInput;
    }
}

