<?php

namespace Database\Seeders;

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
        \DB::table('roles')->insert(array(
            array(
                'id' => '1',
                'name' => 'dev',
                'guard_name' => 'web',
                'created_at' => '2022-07-21 10:37:56',
                'updated_at' => '2022-07-21 10:37:56',
            ),
            array(
                'id' => '2',
                'name' => 'admin',
                'guard_name' => 'web',
                'created_at' => '2022-07-21 10:37:56',
                'updated_at' => '2022-07-21 10:37:56',
            ),
            array(
                'id' => '3',
                'name' => 'client',
                'guard_name' => 'web',
                'created_at' => '2022-07-21 10:37:56',
                'updated_at' => '2022-07-21 10:37:56',
            ),
        ));
    }
}
