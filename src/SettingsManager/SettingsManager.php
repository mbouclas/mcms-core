<?php

namespace Mcms\Core\SettingsManager;


use Mcms\Core\Models\SettingsManager as SettingsManagerModel;

/**
 * Class SettingsManager
 * @package Mcms\Core\SettingsManager
 */
class SettingsManager
{
    /**
     * @var
     */
    private static $instance;
    /**
     * @var SettingsManagerModel
     */
    public $model;

    /**
     * SettingsManager constructor.
     */
    public function __construct()
    {
        $this->model = new SettingsManagerModel();
    }

    public static function find($slug)
    {
        return self::instance()->model->where('slug', $slug)->first();
    }

    /**
     * @return SettingsManager
     */
    private static function instance(){
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function create(array $item)
    {
        return self::instance()->model->create($item);
    }

    /**
     * @param $id
     * @param array $item
     * @return SettingsManagerModel
     */
    public function update($id, array $item)
    {
        $Item = $this->model->find($id);
        $Item->update($item);

        return $Item;
    }

    /**
     * @param array $item
     * @return SettingsManagerModel
     */
    public function store(array $item)
    {

         $Item = $this->model->create($item);
        return $Item;
    }

    /**
     * @param $id
     * @return boolean
     */
    public function destroy($id)
    {
        $item = $this->model->find($id);

        return $item->delete();
    }
}