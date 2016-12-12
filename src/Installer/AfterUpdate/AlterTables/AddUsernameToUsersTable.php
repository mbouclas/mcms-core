<?php

namespace Mcms\Core\Installer\AfterUpdate\AlterTables;


use Carbon\Carbon;
use Mcms\Core\Models\User;
use Illuminate\Console\Command;
use Schema;

class AddUsernameToUsersTable
{
    public function handle(Command $command)
    {
        $migration = '2016_11_10_093345_add_username_to_users.php';
        $targetFile = database_path("migrations/{$migration}");
        if ( ! \File::exists($targetFile)){
            \File::copy(__DIR__ . "/../../../../database/migrations/{$migration}", $targetFile);
        }

        if (! Schema::hasColumn('users', 'username')){
            $command->call('migrate');
            //seed
            $rows = User::all();
            foreach ($rows as $row) {
                $parts = explode('@', $row->email);
                $row->username = $parts[0];
                $row->save();
            }
        }


    }
}