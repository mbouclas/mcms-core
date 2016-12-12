<?php

namespace Mcms\Core\Console\Commands\InstallerActions;


use Illuminate\Console\Command;

class SeedDataBase
{
    public function handle(Command $command)
    {
        $command->call('core:seed:roles');
        $command->call('core:createAdmin');
        $command->call('translations:import');
        $command->comment('* Database seeded');
    }
}