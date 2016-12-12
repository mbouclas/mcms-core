<?php

namespace Mcms\Core\Services\SettingsManager;


use Config;
use Illuminate\Support\Collection;

/**
 * Class SettingsManagerService
 * @package Mcms\Core\Services\SettingsManager
 */
class SettingsManagerService
{
    /**
     * @var
     */
    private static $instance;

    /**
     * @var Collection
     */
    protected $registry;

    /**
     * SettingsManagerService constructor.
     */
    public function __construct()
    {
        $this->registry = new Collection([]);
    }

    /**
     * @return Collection
     */
    public function registry()
    {
        return $this->registry;
    }

    /**
     * @param $name
     * @param array $config
     * @return $this
     */
    protected function add($name, array $config = []){

        $this->registry->push([
            'name' => $name,
            'config' => new Collection($config)
        ]);

        return $this;
    }

    /**
     * @param $name
     * @param $config
     * @return mixed
     */
    public static function register($name, $config)
    {
        if ( ! Config::has($config)){
            return;
        }

        self::instance()->add($name, Config::get($config));

        return self::$instance;
    }

    /**
     * filters can either be a string, like the name of the collection, or an array
     * @example $filters as array $filters = ['name'=>'core','type'=>'generic']
     *
     * @param null $filters
     * @return Collection
     */
    public static function get($filters = null)
    {
        $result = self::instance()->registry();

        if ($filters){
            if ( ! is_array($filters)){
                $result = $result->where('name', $filters);
                return $result->first();
            }

            foreach ($filters as $key=>$filter) {
                $result = $result->where($key, $filter);
            }
        }
        return $result;
    }

    /**
     * @return SettingsManagerService
     */
    private static function instance(){
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
