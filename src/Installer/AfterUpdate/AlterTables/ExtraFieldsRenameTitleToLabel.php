<?php

namespace Mcms\Core\Installer\AfterUpdate\AlterTables;


use Carbon\Carbon;
use Mcms\Core\Models\User;
use Illuminate\Console\Command;
use Schema;

class ExtraFieldsRenameTitleToLabel
{
    public function handle(Command $command)
    {
        $migration = '2016_10_20_103525_extra_fields_rename_title_to_label.php';
        $targetFile = database_path("migrations/{$migration}");
        if ( ! Schema::hasColumn('extra_fields', 'label')){
            \File::copy(__DIR__ . "/../../../../database/migrations/{$migration}", $targetFile);
            $command->call('migrate');
        }


    }
}