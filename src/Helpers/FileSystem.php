<?php

namespace Mcms\Core\Helpers;

use Illuminate\Filesystem\Filesystem as FS;
/**
 * Example of how to target Service providers in app.php
 * $appConfigLine = 'App\Providers\RouteServiceProvider::class,

'.$vendor.'\\'.$name.'\\'.$name.'ServiceProvider::class,';
 * And usage :
 * replaceAndSave(base_path('/config/app.php'), 'App\Providers\RouteServiceProvider::class,', $appConfigLine);
 */


class FileSystem
{
    /**
     * @var FS
     */
    public $fs;

    /**
     * FileSystem constructor.
     * @param FS $filesystem
     */
    public function __construct(FS $filesystem)
    {
        $this->fs = $filesystem;
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
        $file = $this->fs->get($oldFile);
        $replacing = str_replace($search, $replace, $file);
        $this->fs->put($newFile, $replacing);
    }

    public function var_export54($var, $indent="") {

        switch (gettype($var)) {
            case "string":
                return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
            case "array":
                $indexed = array_keys($var) === range(0, count($var) - 1);
                $r = [];
                foreach ($var as $key => $value) {
                    $r[] = "$indent    "
                        . ($indexed ? "" : $this->var_export54($key) . " => ")
                        . $this->var_export54($value, "$indent    ");
                }
                return "[\n" . implode(",\n", $r) . "\n" . $indent . "]";
            case "boolean":
                return $var ? "TRUE" : "FALSE";
            default:
                return var_export($var, TRUE);
        }
    }
}