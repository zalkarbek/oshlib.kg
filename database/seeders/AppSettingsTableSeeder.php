<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('app_settings')->insert(array(
            array(
                'id' => 1,
                'key' => 'date_format',
                'value' => 'l jS F Y (H:i:s)',
            ),
            array(
                'id' => 2,
                'key' => 'language',
                'value' => 'ru',
            ),
            array(
                'id' => 3,
                'key' => 'is_human_date_format',
                'value' => '1',
            ),
            array(
                'id' => 4,
                'key' => 'app_name',
                'value' => 'Food Delivery',
            ),
            array(
                'id' => 5,
                'key' => 'app_short_description',
                'value' => 'Manage Mobile Application',
            ),
            array(
                'id' => 6,
                'key' => 'mail_driver',
                'value' => 'smtp',
            ),
            array(
                'id' => 7,
                'key' => 'mail_host',
                'value' => 'smtp.hostinger.com',
            ),
            array(
                'id' => 8,
                'key' => 'mail_port',
                'value' => '587',
            ),
            array(
                'id' => 9,
                'key' => 'mail_username',
                'value' => 'fooddelivery@smartersvision.com',
            ),
            array(
                'id' => 10,
                'key' => 'mail_password',
                'value' => '',
            ),
            array(
                'id' => 11,
                'key' => 'mail_encryption',
                'value' => 'ssl',
            ),
            array(
                'id' => 12,
                'key' => 'mail_from_address',
                'value' => 'fooddelivery@smartersvision.com',
            ),
            array(
                'id' => 13,
                'key' => 'mail_from_name',
                'value' => 'Smarter Vision',
            ),
            array(
                'id' => 14,
                'key' => 'timezone',
                'value' => 'America/Montserrat',
            ),
            array(
                'id' => 15,
                'key' => 'theme_contrast',
                'value' => 'light',
            ),
            array(
                'id' => 16,
                'key' => 'theme_color',
                'value' => 'primary',
            ),
            array(
                'id' => 17,
                'key' => 'app_logo',
                'value' => '020a2dd4-4277-425a-b450-426663f52633',
            ),
            array(
                'id' => 18,
                'key' => 'nav_color',
                'value' => 'navbar-light bg-white',
            ),
            array(
                'id' => 19,
                'key' => 'logo_bg_color',
                'value' => 'bg-white',
            ),
            array(
                'id' => 22,
                'key' => 'default_role',
                'value' => 'user',
            ),
            array(
                'id' => 23,
                'key' => 'fcm_key',
                'value' => 'AAAAHMZiAQA:APA91bEb71b5sN5jl-w_mmt6vLfgGY5-_CQFxMQsVEfcwO3FAh4-mk1dM6siZwwR3Ls9U0pRDpm96WN1AmrMHQ906GxljILqgU2ZB6Y1TjiLyAiIUETpu7pQFyicER8KLvM9JUiXcfWK',
            ),
            array(
                'id' => 24,
                'key' => 'enable_notifications',
                'value' => '1',
            ),
            array(
                'id' => 25,
                'key' => 'main_color',
                'value' => '#ea5c44',
            ),
            array(
                'id' => 26,
                'key' => 'main_dark_color',
                'value' => '#ea5c44',
            ),
            array(
                'id' => 27,
                'key' => 'second_color',
                'value' => '#344968',
            ),
            array(
                'id' => 28,
                'key' => 'second_dark_color',
                'value' => '#ccccdd',
            ),
            array(
                'id' => 29,
                'key' => 'accent_color',
                'value' => '#8c98a8',
            ),
            array(
                'id' => 30,
                'key' => 'accent_dark_color',
                'value' => '#9999aa',
            ),
            array(
                'id' => 31,
                'key' => 'scaffold_dark_color',
                'value' => '#2c2c2c',
            ),
            array(
                'id' => 32,
                'key' => 'scaffold_color',
                'value' => '#fafafa',
            ),
        ));
    }
}
