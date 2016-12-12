<?php

namespace Mcms\Core\Helpers;


use Dotenv\Dotenv;

class EnvFiles
{
    protected $contents;
    protected $loader;
    public $contentsArray = [];
    protected $envFile;

    public function __construct($path = null, $envFile = '.env')
    {
        $path = ($path) ?: base_path();
        $this->loader = new Dotenv($path, $envFile);
        $this->envFile = $path . '/' . $envFile;
        $this->contents = $this->loader->load();
        $this->parse();
    }

    protected function parse(){
        foreach($this->contents as $one){
            $entry = explode("=", $one, 2);
            $this->contentsArray[$entry[0]] = isset($entry[1]) ? $entry[1] : null;
        }

        return $this;
    }

    public function toArray()
    {
        return $this->contentsArray;
    }

    function toJson() {
        return json_encode($this->contentsArray);
    }

    function toString(){
        $newArray = [];
        $c = 0;
        foreach($this->contentsArray as $key => $value){
            switch ($value){
                case is_bool($value) : $value = ($value) ? "true" : "false";
                    break;
            }

            $newArray[$c] = $key . "=" . $value;
            $c++;
        }
        return implode("\n", $newArray);
    }

    public function save(){
        \File::put($this->envFile, $this->toString());
        return $this;
    }
}