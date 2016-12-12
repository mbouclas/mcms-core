<?php


namespace Mcms\Core\Services\Menu;

use Mcms\Core\Helpers\Strings;
use Mcms\Core\Services\Lang\Contracts\LanguagesContract;


/**
 * Class PermalinkCreator
 * @package Mcms\Core\Services\Menu
 */
class PermalinkCreator
{
    /**
     * @var array
     */
    protected $node;
    /**
     * @var ValidateMenuItem
     */
    protected $validator;
    /**
     * @var Strings
     */
    protected $stringHelpers;

    protected $lang;

    /**
     * PermalinkCreator constructor.
     * @param ValidateMenuItem $validator
     * @param array $node
     */
    public function __construct(ValidateMenuItem $validator, array $node, LanguagesContract $lang)
    {
        $this->validator = $validator;
        $this->node = $node;
        $this->stringHelpers = new Strings();
        $this->lang = $lang;
    }

    /**
     * @return array|mixed|string
     */
    public function handle()
    {
        if ( ! isset($this->node['settings']) || ! is_array($this->node['settings'])){
            $this->node['settings'] = [];
        }

        if ($this->validator->checkIfLinkBuildingIsRequired()){
            return $this->build($this->node);
        }

        if ($this->validator->checkIfItIsCustomLink()) {
            return $this->node;
        }


        return '';
    }

    /**
     * Build all the fields based on the model
     * titleField can either be a string or an array with a pattern property
     * The pattern of title field can be an array of locales, as this is a translatable field
     * @example : titleField => ['en'=>'pattern','es'=>'el patterno']
     *
     * @param array $node
     * @return array
     */
    private function build(array $node)
    {
        // If it is, query it and pass it down to the sprintf
        //set title and permalink
        //get an instance of the model
        $model = new $node['model'];
        $item = $model->find($node['item_id']);
        //check if item has a layout with a custom slug pattern
        $node['permalink'] = $item->getSlug();
        $node['slug_pattern'] = $item->slugPattern();
        $item = $item->toArray();
        //care for translations
        $title = [];
        if (is_string($node['titleField'])) {
            //This is a copy paste situation, don't mind it
            $title = $item[$node['titleField']];
        }
        else {
            //for all available locales, add a title
            //The pattern can be a localized array like so : [en : '', el : '']
            //or a single string, in which case, we apply the same format to all locales

            foreach ($this->lang->locales() as $locale) {
                if (array_key_exists('pattern',$node['titleField']) && is_array($node['titleField']['pattern'])) {
                    //we will check for each locale
                    if (isset($locale['code']) && array_key_exists($locale['code'], $node['titleField']['pattern'])){
                        $title[$locale['code']] = $this->stringHelpers->vksprintf($node['titleField']['pattern'][$locale['code']], $item);
                    }
                    //so what happens if we are missing a locale?
                    //screw it
                } else {
                    $title[$locale['code']] = $this->stringHelpers->vksprintf($node['titleField']['pattern'], $item);
                }
            }
        }

        if ( ! isset($node['settings']) || ! is_array($node['settings'])){
            $node['settings'] = [];
        }

        $node['settings']['titleField'] = $node['titleField'];

        $node['title'] = $title;
        return $node;
    }
}