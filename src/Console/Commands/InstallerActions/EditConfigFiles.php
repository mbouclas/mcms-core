<?php

namespace Mcms\Core\Console\Commands\InstallerActions;


use Mcms\Core\Helpers\ConfigFiles;
use Illuminate\Console\Command;

/**
 * Class EditConfigFiles
 * @package Mcms\Core\Console\Commands\InstallerActions
 */
class EditConfigFiles
{
    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        //Create a new disk for media
        $config = new ConfigFiles('filesystems', true);
        $config->addToArray('disks', "'media' => [
                'driver' => 'local',
                'root' => public_path('images/mediaLibrary')
            ]", true);
        $config->save();
        
        $command->comment('* Done editing config files');
    }
}