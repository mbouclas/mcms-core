<?php

namespace Mcms\Core\Installer\AfterUpdate\AlterTables;


use Carbon\Carbon;
use Mcms\Core\Models\User;
use Illuminate\Console\Command;
use Schema;

class AddMissingColumnsToUsersTable
{
    public function handle(Command $command)
    {
        $migration = '2016_10_20_093345_add_columns_to_users.php';
        $targetFile = database_path("migrations/{$migration}");
        if ( ! \File::exists($targetFile) && ! Schema::hasColumn('users', 'active')){
            \File::copy(__DIR__ . "/../../../../database/migrations/{$migration}", $targetFile);
            $command->call('migrate');
            //seed
            $rows = User::all();
            foreach ($rows as $row) {
                $row->active = true;
                $row->activated_at = Carbon::now();
                $row->awaits_moderation = false;
                $row->save();
            }
        }


    }
}