<?php

namespace Mcms\Core\Installer\AfterUpdate\CreateMissingTable;


use Mcms\Core\SettingsManager\SettingsManager;
use Illuminate\Console\Command;
use Schema;

class CreateExtraFieldsTables
{
    public function handle(Command $command)
    {
        if ( ! Schema::hasTable('extra_fields')){
            $file = '2016_10_01_070253_create_extra_fields_table.php';
            $targetFile = database_path("migrations/{$file}");
            if ( ! \File::exists($targetFile)){
                \File::copy(__DIR__ . "/../../../../database/migrations/{$file}", $targetFile);
                $command->call('migrate');
            }
        }

        if ( ! Schema::hasTable('extra_field_categories')){
            $file = '2016_10_01_070650_create_extra_field_categories_table.php';
            $targetFile = database_path("migrations/{$file}");
            if ( ! \File::exists($targetFile)){
                \File::copy(__DIR__ . "/../../../../database/migrations/{$file}", $targetFile);
            }
            $command->call('migrate');
        }

        if ( ! Schema::hasTable('extra_field_groups')){
            $file = '2016_10_01_070642_create_extra_field_groups_table.php';
            $targetFile = database_path("migrations/{$file}");
            if ( ! \File::exists($targetFile)){
                \File::copy(__DIR__ . "/../../../../database/migrations/{$file}", $targetFile);
            }
            $command->call('migrate');
        }

        if ( ! Schema::hasTable('extra_field_values')){
            $file = '2016_10_01_070827_create_extra_field_values_table.php';
            $targetFile = database_path("migrations/{$file}");
            if ( ! \File::exists($targetFile)){
                \File::copy(__DIR__ . "/../../../../database/migrations/{$file}", $targetFile);
            }
            $command->call('migrate');
        }

        if ( ! Schema::hasTable('extra_field_group_items')){
            $file = '2016_10_01_071900_create_extra_field_group_items_table.php';
            $targetFile = database_path("migrations/{$file}");
            if ( ! \File::exists($targetFile)){
                \File::copy(__DIR__ . "/../../../../database/migrations/{$file}", $targetFile);
            }
            $command->call('migrate');
        }
    }
}