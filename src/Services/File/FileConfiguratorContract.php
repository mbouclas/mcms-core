<?php


namespace Mcms\Core\Services\File;


interface FileConfiguratorContract
{
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
}