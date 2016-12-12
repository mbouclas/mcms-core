<?php


namespace Mcms\Core\Services\Image;


trait ImageConfiguratorConfigurable
{

    /**
     * Configures the Image resizer service providing all configuration options needed
     *
     * @return mixed
     */
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
        return call_user_func($this->savePath,
            'images/' .
            $this->stringHelpers->vksprintf($this->config['dirPattern'], $this->model->toArray())
        );
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
     * @param array $copy
     * @return mixed
     */
    public function formatCopyFileName($file, array $copy)
    {
        $filename = basename($file);
        $path = dirname($file);

        if (isset($copy['prefix'])){
            $filename = $copy['prefix'] . $filename;
        }

        if (isset($copy['suffix'])){
            $fileInfo = pathinfo($file);
            $filename = basename($filename,'.'.$fileInfo['extension']) . $copy['suffix'] . '.' . $fileInfo['extension'];
        }

        if ($copy['dir']){
            $path = $path . '/' . $copy['dir'];
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
        return dirname($file) . '/originals/';
    }
}