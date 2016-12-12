<?php

namespace Mcms\Core\Console\Commands\InstallerActions;


use File;
use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;


/**
 * @example php artisan vendor:publish --provider="Mcms\Core\CoreServiceProvider" --tag=config
 * Class PublishSettings
 * @package Mcms\Core\Console\Commands\InstallerActions
 */
class PublishSettings
{

    /**
     * @param Command $command
     * @param Application $app
     */
    public function handle(Command $command, Application $app)
    {
        $command->call('vendor:publish', [
            '--provider' => 'Mcms\Core\CoreServiceProvider',
            '--tag' => ['config'],
        ]);

        $newConfig = require(config_path('laratrust.php'));
        $app['config']->set('laratrust', $newConfig);

        $command->comment('* Settings published');
    }
}