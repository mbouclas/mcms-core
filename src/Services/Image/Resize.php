<?php


namespace Mcms\Core\Services\Image;
use Config, File;
use Intervention\Image\ImageManager;


class Resize
{
    public $image;

    public function __construct()
    {
        $driver = (Config::get('core.images.driver')) ?: 'imagick';
        $this->image = new ImageManager(array('driver' => $driver));
    }

    public function handle($file, $target, $options)
    {
        $resizeType = (isset($options['resizeType'])) ? $options['resizeType'] : 'resize';
        $targetDir = dirname($target);
        if ( ! File::exists($targetDir)){
            File::makeDirectory($targetDir);
        }
        $quality = (isset($options['quality'])) ? $options['quality'] : null;
        return $this->image
            ->make($file)
            ->{$resizeType}($options['width'], $options['height'])
            ->save($target, $quality);
    }
}