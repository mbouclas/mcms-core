<?php

namespace Mcms\Core\Console\Commands\InstallerActions;


use Illuminate\Console\Command;

/**
 * Class PublishAssets
 * @package Mcms\Core\Console\Commands\InstallerActions
 */
class PublishAssets
{
    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        $command->call('vendor:publish', [
            '--provider' => 'Mcms\Core\CoreServiceProvider',
            '--tag' => ['public'],
        ]);

        $command->comment('* Assets published');
    }
}