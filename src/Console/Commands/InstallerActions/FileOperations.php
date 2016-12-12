<?php

namespace Mcms\Core\Console\Commands\InstallerActions;

use Mcms\Core\Helpers\ConfigFiles;
use Mcms\Core\Helpers\FileSystem as FS;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use \File;
use Illuminate\Console\Command;

class FileOperations
{
    public function handle(Command $command)
    {
        //Delete the default laravel user migration file to allow ours to be written first
        $finder = new Finder();
        $files = $finder->in(base_path('database/migrations'))->name('*create_users_table.php');
        foreach ($files as $file) {
            File::delete($file->getFileInfo());
        }

        //Now we need to swap the default user model to our own
        $config = new ConfigFiles('auth', true);
        $config->addChange('model', '\Mcms\Core\Models\User::class', true);
        $config->save();

        $command->comment('* file operations complete');
    }
}