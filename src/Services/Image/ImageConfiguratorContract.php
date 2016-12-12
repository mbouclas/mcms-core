<?php

namespace Mcms\Core\Services\Image;


interface ImageConfiguratorContract
{
    /**
     * Configures the Image resizer service providing all configuration options needed
     *
     * @return mixed
     */
    public function configure();

    /**
     * Creates the destination path from the configuration $dirPattern
     *
     * @return mixed
     */
    public function uploadPath();


    /**
     * Creates a filename from the configuration $filePattern if any
     *
     * @param null $originalName
     * @return mixed
     */
    public function formatFileName($originalName = null);


    /**
     * Create a filename for the image copy
     *
     * @param $file
     * @param array $copy
     * @return mixed
     */
    public function formatCopyFileName($file, array $copy);


    /**
     * Create a url for this copy
     *
     * @param $file
     * @param array $copy
     * @return mixed
     */
    public function formatCopyUrl($file, array $copy);

    /**
     * Create a folder name for the originals directory
     *
     * @param $file
     * @return mixed
     */
    public function formatOriginalsFolder($file);
}