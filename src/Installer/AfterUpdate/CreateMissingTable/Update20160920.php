<?php

namespace Mcms\Core\Installer\AfterUpdate\CreateMissingTable;


use Illuminate\Console\Command;

class Update20160920
{
    public function handle(Command $command)
    {
        $file = '2016_09_20_091434_create_file_gallery_table.php';
        $targetFile = database_path("migrations/{$file}");
        if ( ! \Schema::hasTable('file_gallery')){
            \File::copy(__DIR__ . "/../../../../database/migrations/{$file}", $targetFile);
            $command->call('migrate');
        }
    }
}