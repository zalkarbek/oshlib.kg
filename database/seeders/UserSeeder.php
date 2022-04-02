<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert(array(
            array(
                'id' => '1',
                'name' => 'Dev',
                'login' => 'dev',
                'email' => 'itgit.kg@gmail.com',
                'password' => '$2y$12$H9ZUAhJz2eVMdDVK5/Z9MeNySAx/ISMMhR4CXD27Nv22UpDTSws16',
                // 'api_token' => 'PivvPlsQWxPl1bB5KrbKNBuraJit0PrUZekQUgtLyTRuyBq921atFtoR1HuA',
                // 'remember_token' => '7hsbgqwOAraSbzKDdaXSjrZR0FDXO4smBiwGx0nKT644yddN2zOCsUUjqJLw',
                'created_at' => '2022-08-06 16:58:41',
                'updated_at' => '2022-08-06 16:58:41',
                'comment' => '',
            ),
            array(
                'id' => '2',
                'name' => 'Admin',
                'login' => 'admin',
                'email' => 'admin@demo.com',
                'password' => '$2y$12$H9ZUAhJz2eVMdDVK5/Z9MeNySAx/ISMMhR4CXD27Nv22UpDTSws16',
                // 'api_token' => 'PivvPlsQWxPl1bB5KrbKNBuraJit0PrUZekQUgtLyTRuyBq921atFtoR1HuA',
                // 'remember_token' => '7hsbgqwOAraSbzKDdaXSjrZR0FDXO4smBiwGx0nKT644yddN2zOCsUUjqJLw',
                'created_at' => '2022-08-06 16:58:41',
                'updated_at' => '2022-08-06 16:58:41',
                'comment' => '',
            ),
        ));
    }
}
