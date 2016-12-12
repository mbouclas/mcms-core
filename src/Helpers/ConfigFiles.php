<?php

namespace Mcms\Core\Helpers;

use Illuminate\Filesystem\Filesystem as FileS;
use Mcms\Core\Helpers\FileSystem as FS;
use Illuminate\Support\Collection;

/**
 * Read a config file, change it's contents then save it. Magic!!!
 *
 * Class ConfigFiles
 * @package Mcms\Core\Helpers
 */
class ConfigFiles
{
    /**
     * @var
     */
    protected $configFile;
    /**
     * @var mixed
     */
    public $contents;
    /**
     * @var \Mcms\Core\Helpers\FileSystem
     */
    protected $fs;

    /**
     * @var Collection
     */
    protected $changesRegistry;

    /**
     * @var bool
     */
    protected $safeProcess;

    /**
     * Pass the config file name
     *
     * ConfigFiles constructor.
     * @param string $configFile
     */
    public function __construct($configFile, $safe = false)
    {
        $this->configFile = $configFile;
        $this->fs = new FS(new FileS());
        $this->safeProcess = $safe;

        if ($safe) {
            $this->contents = $this->fs->fs->get(config_path($configFile . '.php'));
            $this->changesRegistry = new Collection();
            return $this;
        }

        $this->contents = \Config::get($this->configFile);
        return $this;
    }

    /**
     * Grab the config file as an array
     *
     * @return array
     */
    public function contents()
    {
        return $this->contents;
    }

    /**
     * @param $searchFor
     * @param $replaceWith
     * @param bool $treatAsCode
     * @return $this
     */
    public function addChange($searchFor, $replaceWith, $treatAsCode = false)
    {
        $this->changesRegistry->push([
            'searchFor' => $searchFor,
            'replaceWith' => $replaceWith,
            'isFunction' => $treatAsCode
        ]);

        return $this;
    }

    /**
     * @param $searchFor
     * @param $replaceWith
     * @return $this
     */
    private function replace($searchFor, $replaceWith)
    {
        //first see if it is an array
        if (is_array($replaceWith)) {
            $this->processArrayInput($searchFor, $replaceWith);
            return $this;
        }

        $this->processStringInput($searchFor, $replaceWith);

        return $this;
    }

    /**
     * @param $searchFor
     * @param $replaceWith
     * @return $this
     */
    private function processArrayInput($searchFor, $replaceWith){
        //do an array search
        $matchArray = "/([\\'\"]" . $searchFor . "[\\'\"])([\\s]*[\\s]*)=>([\\s]*[\\s]*)(\\[([^\\]]*)\\])/";
        preg_match_all($matchArray, $this->contents, $matches);
        //not found, assume it's something new
        if (empty($matches[4])) {
            //find the end of file
            $this->contents = str_replace('];',
                "'{$searchFor}' => " . $this->fs->var_export54($replaceWith) .",\n ];",
                $this->contents);

            return $this;
        }

        //found, replace values
        $this->contents = preg_replace_callback(
            $matchArray,
            function ($match) use ($replaceWith) {
                return str_replace($match[4], $this->fs->var_export54($replaceWith), $match[0]);
            },
            $this->contents);

        return $this;
    }

    /**
     * Adds a single row in an existing array
     *
     * @param $key
     * @param $value
     * @param bool $treatAsCode
     * @return $this
     */
    public function addToArray($key, $value, $treatAsCode = false){

        //what we need to do first of is find this array
//        $matchArray = "/([\\'\"]" . $key . "[\\'\"])([\\s]*[\\s]*)=>([\\s]*[\\s]*)(\\[([^\\]]*)\\])/";
//        $matchArray = "/([\'\"].*[\'\"])([\\s]*[\\s]*=>[\\s]*[\\s])(\\[([^\\[\\]]|(?R))*\\])/m";
        $matchArray = "~([\'\"][^\'\"]*[\'\"])(\s*=>\s+)(\[(?:[^\[\]])*\s+\])~m";

        preg_match_all($matchArray, $this->contents, $matches);

        $found = null;
        foreach ($matches[1] as $index => $match){
            if (str_replace(["'",'"'],['',''], $match) == $key){
                $found = $index;
            }
        }

        if (is_null($found)){
            $matchCount = count($matches[0]);
            $i = 0;
            $this->contents = preg_replace_callback(
                $matchArray,
                function ($match) use ($key, $value, $found, $matches, $matchCount, &$i) {
                    if($i < $matchCount-1){
                        $i++;
                        return $match[0];
                    }


                    return $matches[0][$matchCount - 1] . ',
                    ' . $value .'';

                },
                $this->contents);
            return $this;
        }

        $this->contents = preg_replace_callback(
            $matchArray,
            function ($match) use ($key, $value, $found, $matches) {

                if (str_replace(["'",'"'],['',''], $match[1]) != $key){
                    return $match[0];
                }


                return trim($matches[0][$found],']') . '' . $value .',
                ]';

            },
            $this->contents);

        return $this;
    }

    /**
     * @param $key
     * @param $contents
     * @param bool $matchExact
     * @return bool
     */
    private function findInArray($key, $contents, $matchExact = false){
        $matchArray = "/([\\'\\\"].*[\\'\\\"])([\\s]*[\\s]*=>[\\s]*[\\s])(\\[([^\\[\\]]|(?R))*\\])/m";

        preg_match_all($matchArray, $contents, $matches);

        $found = null;

        foreach ($matches[1] as $index => $match){
            if (str_replace(["'",'"'],['',''], $match) == $key){
                $found = $index;
                break;
            }
        }

        if ($matchExact){
//            echo "\n\n\n--{$found} / {$key} ===\n\n";
//            print_r($contents);
//            print_r($matches);

        }

        return (is_null($found)) ? false : $matches[3][$found] ;
    }

    /**
     * @param $searchFor
     * @param $replaceWith
     * @return $this
     */
    private function processStringInput($searchFor, $replaceWith){
        //find this change
        $change = $this->changesRegistry->where('searchFor', $searchFor)->first();
        $matchSingleLine =  "/([\\'\"]" . $searchFor . "[\\'\"])([\\s]*[\\s]*)=>([\\s]*[\\s]*)([\\\"\\']?.*[\\\"\\']?)/m";
        preg_match_all($matchSingleLine, $this->contents, $matches);

        $replacement = ($change['isFunction']) ? "{$replaceWith}" : "'{$replaceWith}'";
        if (empty($matches[4])) {
            $this->contents = str_replace('];',
                "'{$searchFor}' => $replacement,\n ];",
                $this->contents);

            return $this;
        }

        //find and replace
        $this->contents =   preg_replace_callback(
            $matchSingleLine,
            function ($match) use ($searchFor, $replacement) {
                return "'{$searchFor}' => " . $replacement . ',
                ';
            },
            $this->contents);
        return $this;
    }

    /**
     * Saves the config file
     */
    public function save($debug = false)
    {
        if (!$this->safeProcess) {
            $out = '<?php
            return ';
            $out .= $this->fs->var_export54($this->contents);
            $out .= ';';
        } else {
            foreach ($this->changesRegistry as $item) {
                $this->replace($item['searchFor'], $item['replaceWith']);
            }

            $out = $this->contents;
        }


        if ($debug){
            return $this;
        }

        return $this->fs->fs->put(config_path($this->configFile . '.php'), $out);
    }
}