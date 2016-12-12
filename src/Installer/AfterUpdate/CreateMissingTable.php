<?php

namespace Mcms\Core\Installer\AfterUpdate;



use Mcms\Core\Installer\AfterUpdate\CreateMissingTable\CreateExtraFieldsTables;
use Mcms\Core\Installer\AfterUpdate\CreateMissingTable\CreateLikedTables;
use Mcms\Core\Installer\AfterUpdate\CreateMissingTable\Update20160920;
use Mcms\Core\Installer\AfterUpdate\CreateMissingTable\Update20161016;
use Mcms\Core\Models\UpdatesLog;
use Illuminate\Console\Command;

class CreateMissingTable
{
    public function handle(Command $command, UpdatesLog $item)
    {
        $classes = [
            Update20160920::class,
            Update20161016::class,
            CreateExtraFieldsTables::class,
            CreateLikedTables::class,
        ];

        foreach ($classes as $class) {
            (new $class())->handle($command);
        }

        $item->result = true;
        $item->save();
        $command->comment('All done in CreateMissingTable');
    }
}