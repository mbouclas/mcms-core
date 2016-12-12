<?php

namespace Mcms\Core\Services\Menu;

use Mcms\Core\Exceptions\InvalidMenuStructureException;
use Validator;


/**
 * Class ValidateMenuItem
 * @package Mcms\Core\Services\Lang
 */
class ValidateMenuItem
{

    /**
     * @var
     */
    protected $node;

    /**
     * Validates a menu item
     *
     * ValidateMenuItem constructor.
     * @param array $node
     */
    public function __construct(array $node)
    {
        $this->node = $node;
    }

    /**
     * Check if the base requirements are fulfilled
     *
     * @return bool
     * @throws InvalidMenuStructureException
     */
    public function baseCheck()
    {
        $check = Validator::make($this->node, [
            'menu_id' => 'required',
            'active' => 'required',
            'sync' => 'required',
        ]);

        if ($check->fails()) {
            throw new InvalidMenuStructureException($check->errors());
        }

        return true;
    }

    /**
     * Check if we need to build a system based link
     *
     * @return bool
     * @throws InvalidMenuStructureException
     */
    public function checkIfLinkBuildingIsRequired()
    {
        $check = Validator::make($this->node, [
            'model' => 'required',
            'item_id' => 'required',
            'titleField' => 'required',
        ]);

        if ($check->fails()) {
            return false;
        }

        return true;
    }

    /**
     * If it is a custom link provided by the user
     *
     * @return bool
     * @throws InvalidMenuStructureException
     */
    public function checkIfItIsCustomLink()
    {
        $check = Validator::make($this->node, [
            'title' => 'required',
            'link' => 'required',
        ]);

        if ($check->fails()) {
            throw new InvalidMenuStructureException($check->errors());
        }

        return true;
    }
}