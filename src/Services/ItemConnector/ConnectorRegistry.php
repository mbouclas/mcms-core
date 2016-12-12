<?php

namespace Mcms\Core\Services\ItemConnector;


use Mcms\Core\Exceptions\InvalidItemConnectorException;
use Illuminate\Support\Collection;

class ConnectorRegistry
{
    protected $registry;
    
    public function __construct()
    {
        $this->registry = new Collection();
    }

    public function register(array $connector)
    {
        try {
            $newConnector = new ConnectorInstance($connector);

            $this->registry->push($newConnector);
        }
        catch (InvalidItemConnectorException $e){
            //Figure out what to do
            print_r($e->getMessage());
        }

        return $this;

    }


    public function connectors(){
        return $this->get();
    }

    public function get()
    {
        $sorted = $this->registry->sortBy('order');
        return $sorted->values();
    }

    public function findConnector(array $query, $sectionName = null)
    {
        $connector = $this->registry;
        foreach ($query as $key => $value) {
            $connector = $connector->where($key, $value);
        }
        
        if ( ! $sectionName){
            return $connector->first();  
        }

        $connector = $connector->first();
        $connector->section = $this->findSection($connector, $sectionName);
        
        return $connector;
    }

    public function findSection(ConnectorInstance $connector, $name)
    {
        $section = [];
        foreach ($connector->connector['sections'] as $item) {
            if ($item['name'] == $name){
                $section = $item;
            }
        }
        
        return $section;
    }
}