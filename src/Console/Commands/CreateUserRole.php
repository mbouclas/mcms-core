<?php

namespace Mcms\Core\Console\Commands;

use Mcms\Core\Models\Role;
use Illuminate\Console\Command;
use Cocur\Slugify\Slugify;

class CreateUserRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:createRole {role} {--slug=} {--description=} {--level=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user role';

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
        //
        $slugify = new Slugify();
        Role::create([
            'display_name' => $this->argument('role'),
            'name' => ($this->option('slug')) ?: $slugify->slugify($this->argument('role'),'.'),
            'description' => ($this->option('description')) ?: '', // optional
            'level' => ($this->option('level')) ?: 1, // optional, set to 1 by default
        ]);

        return true;
    }
}
