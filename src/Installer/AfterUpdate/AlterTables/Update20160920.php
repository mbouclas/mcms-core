<?php

namespace Mcms\Core\Installer\AfterUpdate\AlterTables;


use Illuminate\Console\Command;
use Schema;

class Update20160920
{
    public function handle(Command $command)
    {
        $imagesMigration = '2016_09_20_071339_add_column_to_images.php';
        $relatedMigration = '2016_10_03_082929_add_column_to_related.php';
        $imagesTargetFile = database_path("migrations/{$imagesMigration}");
        $relatedTargetFile = database_path("migrations/{$relatedMigration}");
        if ( ! Schema::hasColumn('images', 'description')){
            \File::copy(__DIR__ . "/../../../../database/migrations/{$imagesMigration}", $imagesTargetFile);
            $command->call('migrate');
        }

        if ( ! Schema::hasColumn('related', 'dest_model')){
            \File::copy(__DIR__ . "/../../../../database/migrations/{$relatedMigration}", $relatedTargetFile);
            $command->call('migrate');
        }
    }
}