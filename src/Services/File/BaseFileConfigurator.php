<?php

namespace Mcms\Core\Services\File;

use App;
use Config;
use Mcms\Core\Helpers\Strings;
use Illuminate\Support\Collection;

class BaseFileConfigurator implements FileConfiguratorContract
{
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
        $this->config = Config::get('core.files');
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

    public function configure()
    {
        return $this->config;
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

    public function formatUrl($file)
    {
        return str_replace(call_user_func($this->savePath), '', $file);
    }
}