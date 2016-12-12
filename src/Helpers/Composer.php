<?php

namespace Mcms\Core\Helpers;
use \File;

class Composer
{
    public $contents;
    protected $composerPath;

    public function __construct()
    {
        $this->composerPath = base_path('composer.json');
        $composerFile = File::get($this->composerPath);
        $this->contents = json_decode($composerFile, true);
    }

    public function save()
    {
        File::put($this->composerPath, json_encode($this->contents, JSON_PRETTY_PRINT));
    }
}