<?php

namespace Mcms\Core\Installer\AfterUpdate\CreateMissingTable;


use Illuminate\Console\Command;
use Schema;

class CreateDynamicTablesTables
{
    public function handle(Command $command)
    {
        if ( ! Schema::hasTable('dynamic_tables')){
            $file = '2017_02_06_095746_create_dynamic_tables_table.php';
            $targetFile = database_path("migrations/{$file}");

            if ( ! \File::exists($targetFile)){
                \File::copy(__DIR__ . "/../../../../database/migrations/{$file}", $targetFile);
                $command->call('migrate');
            }
        }


        if ( ! Schema::hasTable('dynamic_tables_items')){
            $file = '2017_02_06_105747_create_dynamic_tables_items_table.php';
            $targetFile = database_path("migrations/{$file}");

            if ( ! \File::exists($targetFile)){
                \File::copy(__DIR__ . "/../../../../database/migrations/{$file}", $targetFile);
                $command->call('migrate');
            }
        }
    }
}