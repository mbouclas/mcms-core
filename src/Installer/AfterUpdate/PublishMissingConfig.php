<?php

namespace Mcms\Core\Installer\AfterUpdate;


use Mcms\Core\Models\UpdatesLog;
use Illuminate\Console\Command;

class PublishMissingConfig
{
    public function handle(Command $command, UpdatesLog $item)
    {
        $classes = [

        ];

        foreach ($classes as $class) {
            (new $class())->handle($command);
        }
        $item->result = true;
        $item->save();
        $command->comment('All done in PublishMissingConfig');
    }
}