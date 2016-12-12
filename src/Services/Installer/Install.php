<?php

namespace Mcms\Core\Services\Installer;

use Illuminate\Support\Collection;
use Illuminate\Console\Command;
/**
 * Responsible for installing the CMS.
 * Class Install
 * @package Mcms\Core\Services\Installer
 */
class Install
{
    use RegisterInstaller;

    protected $packages;
    protected $installers;
    protected $command;

    public function __construct()
    {
        $this->packages = new Collection();
        $this->installers = new Collection();
    }

    /**
     * Get all register installer packages
     * @return Collection
     */
    public function get()
    {
        return $this->packages;
    }

    /**
     * get all the installers instantiated
     * @return Collection
     */
    public function getInstallers()
    {
        return $this->installers;
    }

    /**
     * Prepare packages for installation
     */
    public function prepare(Command $command)
    {
        $this->command = $command;
        /**
         * If a registered package does not implement the installer interface
         * then discard it.
         */
        $this->packages->each(function ($package){
            if (! array_key_exists('Mcms\Core\Services\Installer\InstallerContract',class_implements($package))){
                return;
            }

            //resolve each class
            $this->installers->push(new $package);
        });

        return $this;

    }


    /**
     * Run the installer and pray
     */
    public function execute($args = [], $bar=null){
        $this->installers->each(function ($installer) use ($args, $bar) {
            if (! array_key_exists($installer->packageName(), $args)){
                $args[$installer->packageName()] = [];
            }

            $installer->run($this->command, $args[$installer->packageName()]);
            $bar->advance();
            $this->command->line('');
        });

        $bar->finish();
    }

}