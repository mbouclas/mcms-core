<?php

namespace Mcms\Core\Installer\AfterUpdate;

use Mcms\Core\Installer\AfterUpdate\AlterTables\AddMissingColumnsToUsersTable;
use Mcms\Core\Installer\AfterUpdate\AlterTables\AddUsernameToUsersTable;
use Mcms\Core\Installer\AfterUpdate\AlterTables\ExtraFieldsRenameTitleToLabel;
use Mcms\Core\Installer\AfterUpdate\AlterTables\Update20160920;
use Mcms\Core\Models\UpdatesLog;
use Illuminate\Console\Command;

class AlterTables
{
    public function handle(Command $command, UpdatesLog $item)
    {
        $classes = [
            Update20160920::class,
            AddMissingColumnsToUsersTable::class,
            ExtraFieldsRenameTitleToLabel::class,
            AddUsernameToUsersTable::class,
        ];

        foreach ($classes as $class) {
            (new $class())->handle($command);
        }


        $item->result = true;
        $item->save();
        $command->comment('All done in AlterTables');
    }
}