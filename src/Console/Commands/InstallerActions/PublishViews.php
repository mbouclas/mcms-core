<?php

namespace Mcms\Core\Console\Commands\InstallerActions;


use Illuminate\Console\Command;

/**
 * Class PublishViews
 * @package Mcms\Core\Console\Commands\InstallerActions
 */
class PublishViews
{
    /**
     * @param Command $command
     */
    public function handle(Command $command)
    {
        $command->call('vendor:publish', [
            '--provider' => 'Mcms\Core\CoreServiceProvider',
            '--tag' => ['views'],
        ]);
        
        $command->comment('* Views published');
    }
}