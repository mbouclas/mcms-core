<?php

namespace Mcms\Core\Services\ItemConnector;

use Mcms\Core\Exceptions\InvalidItemConnectorException;
use Illuminate\Support\Collection;
use Validator;

class ConnectorInstance
{
    /**
     * @var
     */
    public $name;
    /**
     * @var
     */
    public $connector;

    /**
     * ConnectorInstance constructor.
     * @param $connector
     */
    public function __construct(array $connector)
    {
        $check = Validator::make($connector, [
            'name' => 'required',
            'connector' => 'required'
        ]);

        if ($check->fails()){
            throw new InvalidItemConnectorException($check->messages());
        }

        $this->name = $connector['name'];
        $this->type = (isset($connector['type'])) ? $connector['type'] : 'generic';
        $this->order = (isset($connector['connector']['order'])) ? $connector['connector']['order'] : 0;
        $this->connector = $connector['connector'];

        /**
         * Do some validation
         */
        return new Collection($connector);
    }
}