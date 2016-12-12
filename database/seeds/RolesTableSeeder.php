<?php
use Mcms\Core\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'The admin user', // optional
            'level' => 2, // optional, set to 1 by default
        ]);

        Role::create([
            'name' => 'Super User',
            'slug' => 'su',
            'description' => 'The super user', // optional
            'level' => 1, // optional, set to 1 by default
        ]);

        Role::create([
            'name' => 'Moderator',
            'slug' => 'moderator',
            'description' => 'The moderator', // optional
            'level' => 3, // optional, set to 1 by default
        ]);
    }
}
