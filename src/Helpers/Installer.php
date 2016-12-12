<?php

namespace Mcms\Core\Helpers;

use Illuminate\Filesystem\Filesystem;


/**
 * Class Installer
 * @package Mcms\Core\Helpers
 */
class Installer
{

    /**
     * The filesystem handler.
     * @var object
     */
    protected $files;

    /**
     * Installer constructor.
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->files = $filesystem;
    }

    /**
     * Open haystack, find and replace needles, save haystack.
     *
     * @param  string $oldFile The haystack
     * @param  mixed  $search  String or array to look for (the needles)
     * @param  mixed  $replace What to replace the needles for?
     * @param  string $newFile Where to save, defaults to $oldFile
     *
     * @return void
     */
    public function replaceAndSave($oldFile, $search, $replace, $newFile = null)
    {
        $newFile = ($newFile == null) ? $oldFile : $newFile;
        $file = $this->files->get($oldFile);
        $replacing = str_replace($search, $replace, $file);
        $this->files->put($newFile, $replacing);
    }
}