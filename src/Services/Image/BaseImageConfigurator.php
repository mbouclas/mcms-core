<?php

namespace Mcms\Core\Services\Image;


use App;
use Config;
use Mcms\Core\Helpers\Strings;
use Illuminate\Support\Collection;

class BaseImageConfigurator implements ImageConfiguratorContract
{
    use ImageConfiguratorConfigurable;
    /**
     * @var
     */
    public $model;
    /**
     * @var string
     */
    public $savePath;
    /**
     * @var mixed
     */
    protected $config;
    /**
     * @var Strings
     */
    protected $stringHelpers;
    /**
     * @var string
     */
    protected $basePath;
    /**
     * @var string
     */
    protected $baseUrl;

    public function __construct()
    {
        $this->model = new Collection();
        $this->config = Config::get('core.images');
        $uploadDir = (isset($this->config['filePattern'])) ? $this->config['filePattern'] : 'uploads';
        $this->stringHelpers = new Strings();
        $this->basePath = $uploadDir . '/';
        $this->baseUrl = "/{$uploadDir}/";
        if (isset($this->config['savePath'])){
            $this->savePath = $this->config['savePath'];
        } else {
            $this->savePath = (App::environment() == 'production') ? 'storage_path' : 'public_path';
        }
    }


    /**
     * Creates the destination path from the configuration $dirPattern
     *
     * @return mixed
     */
    public function uploadPath()
    {
        return call_user_func($this->savePath, $this->basePath);
    }

    /**
     * Creates a filename from the configuration $filePattern if any
     *
     * @return mixed
     */
    public function formatFileName($originalName = null)
    {
        return null;
    }

    /**
     * Create a filename for the image copy
     *
     * @param $file
     * @param array $copy
     * @return mixed
     */
    public function formatCopyFileName($file, array $copy)
    {
        if ( ! $copy){
            return null;
        }
        $fileInfo = pathinfo($file);
        $filename = uniqid(). '.' . $fileInfo['extension'];
        $path = dirname($file) . "/";

        if (isset($copy['prefix'])){
            $filename = $copy['prefix'] . $filename;
        }

        if (isset($copy['suffix'])){

            $filename = uniqid() . $copy['suffix'] . '.' . $fileInfo['extension'];
        }

        if (isset($copy['dir'])){
            $path = $path . $copy['dir'];
        }

        return $path . $filename;
    }

    /**
     * Create a url for this copy
     *
     * @param $file
     * @param array $copy
     * @return mixed
     */
    public function formatCopyUrl($file, array $copy)
    {
        return str_replace(call_user_func($this->savePath), '', $file);
    }

    /**
     * Create a folder name for the originals directory
     *
     * @param $file
     * @return mixed
     */
    public function formatOriginalsFolder($file)
    {
        return null;
    }
}