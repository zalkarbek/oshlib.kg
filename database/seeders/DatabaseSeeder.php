<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();
        $this->call(AppSettingsTableSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(RoleHasPermissionsSeeder::class);
        $this->call(ModelHasRolesSeeder::class);
        $this->call(NewPermissionsTableSeeder::class);
    }
}
