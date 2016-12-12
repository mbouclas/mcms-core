<?php

namespace Mcms\Core\Models;

use Mcms\Core\Helpers\Strings;
use Spatie\MediaLibrary\Media;
use Spatie\MediaLibrary\PathGenerator\PathGenerator;
class MediaPathGenerator implements PathGenerator
{
    protected $stringHelpers;

    public function __construct()
    {
        $this->stringHelpers = new Strings();
    }
    /*
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media) : string
    {
        $model = new $media->model_type;
        $config = (new $model->imageConfigurator)->config;

        return $this->stringHelpers->vksprintf($config['dirPattern'], $media->toArray()) . "/";
    }
    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     * @return string
     */
    public function getPathForConversions(Media $media) : string
    {
        $model = new $media->model_type;
        $config = (new $model->imageConfigurator)->config;

        return $this->stringHelpers->vksprintf($config['dirPattern'], $media->toArray()) . "/copies/";
    }
}