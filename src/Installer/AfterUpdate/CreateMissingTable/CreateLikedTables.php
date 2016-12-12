<?php

namespace Mcms\Core\Installer\AfterUpdate\CreateMissingTable;


use Illuminate\Console\Command;
use Schema;

class CreateLikedTables
{
    public function handle(Command $command)
    {
        if ( ! Schema::hasTable('likeable_likes')){
            $command->call('vendor:publish', [
                '--provider' => 'Conner\Likeable\LikeableServiceProvider',
                '--tag' => ['migrations'],
            ]);

            $command->call('migrate');
        }
    }
}