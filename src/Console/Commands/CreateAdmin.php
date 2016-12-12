<?php

namespace Mcms\Core\Console\Commands;

use Carbon\Carbon;
use Hash;
use Mcms\Core\Models\Role;
use Mcms\Core\Models\User;
use Illuminate\Console\Command;

use Cocur\Slugify\Slugify;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:createAdmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new admin into the system';

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
        $this->info('Let\'s create a super user');
        $name = $this->ask('Name (first last)');
        $email = $this->ask('email');
        $password = $this->ask('password');

        $slug = 'su';
        $role = Role::where('name',$slug)->first();
        $name = explode(' ', $name);
        User::create([
            'firstName' => $name[0],
            'lastName' => (isset($name[1])) ?: '',
            'email' => $email,
            'password' => Hash::make($password),
            'settings' => ['subscribeToNewsletter' => false],
            'activated_at' => Carbon::now(),
            'active' => true,
        ])->attachRole($role);

        return true;

    }
}
