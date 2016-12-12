<?php

namespace Mcms\Core\Console\Commands;

use Mcms\Core\Models\Role;
use Illuminate\Console\Command;


class SeedUserRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:seed:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the Roles table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Role::create([
            'display_name' => 'Power Administrator',
            'name' => 'admin',
            'description' => 'The power admin user', // optional
            'level' => 98, // optional, set to 1 by default
        ]);

        Role::create([
            'display_name' => 'Administrator',
            'name' => 'small-admin',
            'description' => 'The admin user', // optional
            'level' => 50, // optional, set to 1 by default
        ]);


        Role::create([
            'display_name' => 'Super User',
            'name' => 'su',
            'description' => 'The super user', // optional
            'level' => 99, // optional, set to 1 by default
        ]);

        Role::create([
            'display_name' => 'Moderator',
            'name' => 'moderator',
            'description' => 'The moderator', // optional
            'level' => 2, // optional, set to 1 by default
        ]);

        Role::create([
            'display_name' => 'User',
            'name' => 'user',
            'description' => 'The plain ol user', // optional
            'level' => 1, // optional, set to 1 by default
        ]);

        return true;
    }
}
