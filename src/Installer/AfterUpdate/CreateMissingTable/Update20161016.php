<?php

namespace Mcms\Core\Installer\AfterUpdate\CreateMissingTable;


use Mcms\Core\Models\SettingsManager;
use Illuminate\Console\Command;

class Update20161016
{
    public function handle(Command $command)
    {
        $file = '2016_10_16_090357_create_settings_manager_table.php';
        $targetFile = database_path("migrations/{$file}");
        if ( ! \Schema::hasTable('settings_manager')){
            \File::copy(__DIR__ . "/../../../../database/migrations/{$file}", $targetFile);
            $command->call('migrate');
        }

        $usersProfileField = SettingsManager::where('slug','user-profiles')->first();
        if ( ! $usersProfileField){
            SettingsManager::create([
                'name' => 'User Profiles',
                'slug' => 'user-profiles',
                'fields' => []
            ]);
        }
    }
}