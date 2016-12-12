<?php


namespace Mcms\Core\Services\Menu\Contracts;


/**
 * Responsible for providing a contract to anyone that wants to connect
 * to the admin interface
 *
 * Interface AdminInterfaceConnectorContract
 * @package Mcms\Core\Services\Menu\Contracts
 */
interface AdminInterfaceConnectorContract
{
    /**
     * Called when you want to filter out results from your model
     *
     * @return array
     */
    public function filter($filters);
}