<?php


namespace Mcms\Core\Services\Installer;
use Illuminate\Console\Command;


/**
 * Responsible for creating a new installer
 * Interface InstallerContract
 * @package Mcms\Core\Services\Installer
 */
interface InstallerContract
{


    /**
     * @param array $args
     * @return mixed
     */
    public function run(Command $command, $commands = []);

    /**
     * The package name
     * @return string
     */
    public function packageName();

    /**
     * @return array
     */
    public function requiredInput();

    /**
     * Executed just before the installer runs
     * @return mixed
     */
    public function beforeRun();

    /**
     * Executed after the installer has run
     * @return mixed
     */
    public function afterRun();
}