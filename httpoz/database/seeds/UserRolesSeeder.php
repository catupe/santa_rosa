<?php

use Illuminate\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // 1. query the Roles by the slug
        $adminRole = \HttpOz\Roles\Models\Role::findBySlug('admin');
        $moderatorRole = \HttpOz\Roles\Models\Role::findBySlug('forum.moderator');

        /*
        \HttpOz\Roles\Models\Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Custodians of the system.', // optional
            'group' => 'default' // optional, set as 'default' by default
        ]);
        */
        // 2a. Create admin
        $admin = \App\User::create([
            'name' => 'Oscar Mwanandimai',
            'email' => 'oscar@github.com',
            'password' => bcrypt('password')
        ]);
        /*
        \HttpOz\Roles\Models\Role::create([
            'name' => 'Forum Moderator',
            'slug' => 'forum.moderator',
        ]);
        */
        // 2b. Create forum moderator
        $moderator = \App\User::create([
            'name' => 'John Doe',
            'email' => 'john@github.com',
            'password' => bcrypt('password')
        ]);

        // 3. Attach a role to the user object / assign a role to a user
        $admin->attachRole($adminRole);
        $moderator->attachRole($moderatorRole);
    }
}
