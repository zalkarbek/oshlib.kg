<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('permissions')->delete();

        \DB::table('permissions')->insert(array (
            0 =>
            array (
                'created_at' => '2020-03-29 08:58:02',
                'guard_name' => 'web',
                'id' => 1,
                'name' => 'users.profile',
                'updated_at' => '2020-03-29 08:58:02',
            ),
            1 =>
            array (
                'created_at' => '2020-03-29 08:58:02',
                'guard_name' => 'web',
                'id' => 2,
                'name' => 'dashboard',
                'updated_at' => '2020-03-29 08:58:02',
            ),
            2 =>
            array (
                'created_at' => '2020-03-29 08:58:02',
                'guard_name' => 'web',
                'id' => 3,
                'name' => 'medias.create',
                'updated_at' => '2020-03-29 08:58:02',
            ),
            3 =>
            array (
                'created_at' => '2020-03-29 08:58:02',
                'guard_name' => 'web',
                'id' => 4,
                'name' => 'medias.delete',
                'updated_at' => '2020-03-29 08:58:02',
            ),
            4 =>
            array (
                'created_at' => '2020-03-29 08:58:03',
                'guard_name' => 'web',
                'id' => 5,
                'name' => 'medias',
                'updated_at' => '2020-03-29 08:58:03',
            ),
            5 =>
            array (
                'created_at' => '2020-03-29 08:58:03',
                'guard_name' => 'web',
                'id' => 6,
                'name' => 'permissions.index',
                'updated_at' => '2020-03-29 08:58:03',
            ),
            6 =>
            array (
                'created_at' => '2020-03-29 08:58:03',
                'guard_name' => 'web',
                'id' => 7,
                'name' => 'permissions.edit',
                'updated_at' => '2020-03-29 08:58:03',
            ),
            7 =>
            array (
                'created_at' => '2020-03-29 08:58:03',
                'guard_name' => 'web',
                'id' => 8,
                'name' => 'permissions.update',
                'updated_at' => '2020-03-29 08:58:03',
            ),
            8 =>
            array (
                'created_at' => '2020-03-29 08:58:03',
                'guard_name' => 'web',
                'id' => 9,
                'name' => 'permissions.destroy',
                'updated_at' => '2020-03-29 08:58:03',
            ),
            9 =>
            array (
                'created_at' => '2020-03-29 08:58:03',
                'guard_name' => 'web',
                'id' => 10,
                'name' => 'roles.index',
                'updated_at' => '2020-03-29 08:58:03',
            ),
            10 =>
            array (
                'created_at' => '2020-03-29 08:58:03',
                'guard_name' => 'web',
                'id' => 11,
                'name' => 'roles.edit',
                'updated_at' => '2020-03-29 08:58:03',
            ),
            11 =>
            array (
                'created_at' => '2020-03-29 08:58:03',
                'guard_name' => 'web',
                'id' => 12,
                'name' => 'roles.update',
                'updated_at' => '2020-03-29 08:58:03',
            ),
            12 =>
            array (
                'created_at' => '2020-03-29 08:58:03',
                'guard_name' => 'web',
                'id' => 13,
                'name' => 'roles.destroy',
                'updated_at' => '2020-03-29 08:58:03',
            ),
            13 =>
            array (
                'created_at' => '2020-03-29 08:58:03',
                'guard_name' => 'web',
                'id' => 14,
                'name' => 'universities.index',
                'updated_at' => '2020-03-29 08:58:03',
            ),
            14 =>
            array (
                'created_at' => '2020-03-29 08:58:03',
                'guard_name' => 'web',
                'id' => 15,
                'name' => 'universities.create',
                'updated_at' => '2020-03-29 08:58:03',
            ),
            15 =>
            array (
                'created_at' => '2020-03-29 08:58:03',
                'guard_name' => 'web',
                'id' => 16,
                'name' => 'universities.store',
                'updated_at' => '2020-03-29 08:58:03',
            ),
            16 =>
            array (
                'created_at' => '2020-03-29 08:58:03',
                'guard_name' => 'web',
                'id' => 17,
                'name' => 'universities.show',
                'updated_at' => '2020-03-29 08:58:03',
            ),
            17 =>
            array (
                'created_at' => '2020-03-29 08:58:03',
                'guard_name' => 'web',
                'id' => 18,
                'name' => 'universities.edit',
                'updated_at' => '2020-03-29 08:58:03',
            ),
            18 =>
            array (
                'created_at' => '2020-03-29 08:58:04',
                'guard_name' => 'web',
                'id' => 19,
                'name' => 'universities.update',
                'updated_at' => '2020-03-29 08:58:04',
            ),
            19 =>
            array (
                'created_at' => '2020-03-29 08:58:04',
                'guard_name' => 'web',
                'id' => 20,
                'name' => 'universities.destroy',
                'updated_at' => '2020-03-29 08:58:04',
            ),
            20 =>
            array (
                'created_at' => '2020-03-29 08:58:04',
                'guard_name' => 'web',
                'id' => 21,
                'name' => 'users.login-as-user',
                'updated_at' => '2020-03-29 08:58:04',
            ),
            21 =>
            array (
                'created_at' => '2020-03-29 08:58:04',
                'guard_name' => 'web',
                'id' => 22,
                'name' => 'users.index',
                'updated_at' => '2020-03-29 08:58:04',
            ),
            22 =>
            array (
                'created_at' => '2020-03-29 08:58:04',
                'guard_name' => 'web',
                'id' => 23,
                'name' => 'users.create',
                'updated_at' => '2020-03-29 08:58:04',
            ),
            23 =>
            array (
                'created_at' => '2020-03-29 08:58:04',
                'guard_name' => 'web',
                'id' => 24,
                'name' => 'users.store',
                'updated_at' => '2020-03-29 08:58:04',
            ),
            24 =>
            array (
                'created_at' => '2020-03-29 08:58:04',
                'guard_name' => 'web',
                'id' => 25,
                'name' => 'users.show',
                'updated_at' => '2020-03-29 08:58:04',
            ),
            25 =>
            array (
                'created_at' => '2020-03-29 08:58:04',
                'guard_name' => 'web',
                'id' => 26,
                'name' => 'users.edit',
                'updated_at' => '2020-03-29 08:58:04',
            ),
            26 =>
            array (
                'created_at' => '2020-03-29 08:58:04',
                'guard_name' => 'web',
                'id' => 27,
                'name' => 'users.update',
                'updated_at' => '2020-03-29 08:58:04',
            ),
            27 =>
            array (
                'created_at' => '2020-03-29 08:58:04',
                'guard_name' => 'web',
                'id' => 28,
                'name' => 'users.destroy',
                'updated_at' => '2020-03-29 08:58:04',
            ),
            28 =>
            array (
                'created_at' => '2020-03-29 08:58:04',
                'guard_name' => 'web',
                'id' => 29,
                'name' => 'app-settings',
                'updated_at' => '2020-03-29 08:58:04',
            ),
            29 =>
            array (
                'created_at' => '2020-03-29 08:58:07',
                'guard_name' => 'web',
                'id' => 30,
                'name' => 'galleries.index',
                'updated_at' => '2020-03-29 08:58:07',
            ),
            30 =>
            array (
                'created_at' => '2020-03-29 08:58:07',
                'guard_name' => 'web',
                'id' => 31,
                'name' => 'galleries.create',
                'updated_at' => '2020-03-29 08:58:07',
            ),
            31 =>
            array (
                'created_at' => '2020-03-29 08:58:08',
                'guard_name' => 'web',
                'id' => 32,
                'name' => 'galleries.store',
                'updated_at' => '2020-03-29 08:58:08',
            ),
            32 =>
            array (
                'created_at' => '2020-03-29 08:58:08',
                'guard_name' => 'web',
                'id' => 33,
                'name' => 'galleries.edit',
                'updated_at' => '2020-03-29 08:58:08',
            ),
            33 =>
            array (
                'created_at' => '2020-03-29 08:58:08',
                'guard_name' => 'web',
                'id' => 34,
                'name' => 'galleries.update',
                'updated_at' => '2020-03-29 08:58:08',
            ),
            34 =>
            array (
                'created_at' => '2020-03-29 08:58:08',
                'guard_name' => 'web',
                'id' => 35,
                'name' => 'galleries.destroy',
                'updated_at' => '2020-03-29 08:58:08',
            ),
            35 =>
            array (
                'created_at' => '2020-03-29 08:59:15',
                'guard_name' => 'web',
                'id' => 36,
                'name' => 'permissions.create',
                'updated_at' => '2020-03-29 08:59:15',
            ),
            36 =>
            array (
                'created_at' => '2020-03-29 08:59:15',
                'guard_name' => 'web',
                'id' => 37,
                'name' => 'permissions.store',
                'updated_at' => '2020-03-29 08:59:15',
            ),
            37 =>
            array (
                'created_at' => '2020-03-29 08:59:15',
                'guard_name' => 'web',
                'id' => 38,
                'name' => 'permissions.show',
                'updated_at' => '2020-03-29 08:59:15',
            ),
            38 =>
            array (
                'created_at' => '2020-03-29 08:59:15',
                'guard_name' => 'web',
                'id' => 39,
                'name' => 'roles.create',
                'updated_at' => '2020-03-29 08:59:15',
            ),
            39 =>
            array (
                'created_at' => '2020-03-29 08:59:15',
                'guard_name' => 'web',
                'id' => 40,
                'name' => 'roles.store',
                'updated_at' => '2020-03-29 08:59:15',
            ),
            40 =>
            array (
                'created_at' => '2020-03-29 08:59:16',
                'guard_name' => 'web',
                'id' => 41,
                'name' => 'roles.show',
                'updated_at' => '2020-03-29 08:59:16',
            ),
            41 =>
            array (
                'created_at' => '2022-04-04 04:16:44',
                'guard_name' => 'web',
                'id' => 42,
                'name' => 'categories.index',
                'updated_at' => '2022-04-04 04:16:44',
            ),
            42 =>
            array (
                'created_at' => '2022-04-04 04:17:10',
                'guard_name' => 'web',
                'id' => 43,
                'name' => 'categories.edit',
                'updated_at' => '2022-04-04 04:17:10',
            ),
            43 =>
            array (
                'created_at' => '2022-04-04 04:17:18',
                'guard_name' => 'web',
                'id' => 44,
                'name' => 'categories.update',
                'updated_at' => '2022-04-04 04:17:18',
            ),
            44 =>
            array (
                'created_at' => '2022-04-04 04:17:29',
                'guard_name' => 'web',
                'id' => 45,
                'name' => 'categories.destroy',
                'updated_at' => '2022-04-04 04:17:29',
            ),
            45 =>
            array (
                'created_at' => '2022-04-04 04:30:00',
                'guard_name' => 'web',
                'id' => 46,
                'name' => 'categories.create',
                'updated_at' => '2022-04-04 04:30:00',
            ),
            46 =>
            array (
                'created_at' => '2022-04-04 04:30:09',
                'guard_name' => 'web',
                'id' => 47,
                'name' => 'categories.store',
                'updated_at' => '2022-04-04 04:30:09',
            ),
            47 =>
            array (
                'created_at' => '2022-04-04 04:43:09',
                'guard_name' => 'web',
                'id' => 48,
                'name' => 'tags.index',
                'updated_at' => '2022-04-04 04:43:09',
            ),
            48 =>
            array (
                'created_at' => '2022-04-04 04:43:19',
                'guard_name' => 'web',
                'id' => 49,
                'name' => 'tags.create',
                'updated_at' => '2022-04-04 04:43:19',
            ),
            49 =>
            array (
                'created_at' => '2022-04-04 04:43:34',
                'guard_name' => 'web',
                'id' => 50,
                'name' => 'tags.store',
                'updated_at' => '2022-04-04 04:43:34',
            ),
            50 =>
            array (
                'created_at' => '2022-04-04 04:43:49',
                'guard_name' => 'web',
                'id' => 51,
                'name' => 'tags.edit',
                'updated_at' => '2022-04-04 04:43:49',
            ),
            51 =>
            array (
                'created_at' => '2022-04-04 04:43:56',
                'guard_name' => 'web',
                'id' => 52,
                'name' => 'tags.update',
                'updated_at' => '2022-04-04 04:43:56',
            ),
            52 =>
            array (
                'created_at' => '2022-04-04 04:44:09',
                'guard_name' => 'web',
                'id' => 53,
                'name' => 'tags.destroy',
                'updated_at' => '2022-04-04 04:44:09',
            ),
            53 =>
            array (
                'created_at' => '2022-04-04 07:24:48',
                'guard_name' => 'web',
                'id' => 54,
                'name' => 'authors.index',
                'updated_at' => '2022-04-04 07:24:48',
            ),
            54 =>
            array (
                'created_at' => '2022-04-04 07:24:58',
                'guard_name' => 'web',
                'id' => 55,
                'name' => 'authors.create',
                'updated_at' => '2022-04-04 07:24:58',
            ),
            55 =>
            array (
                'created_at' => '2022-04-04 07:25:07',
                'guard_name' => 'web',
                'id' => 56,
                'name' => 'authors.store',
                'updated_at' => '2022-04-04 07:25:07',
            ),
            56 =>
            array (
                'created_at' => '2022-04-04 07:25:15',
                'guard_name' => 'web',
                'id' => 57,
                'name' => 'authors.edit',
                'updated_at' => '2022-04-04 07:25:15',
            ),
            57 =>
            array (
                'created_at' => '2022-04-04 07:25:25',
                'guard_name' => 'web',
                'id' => 58,
                'name' => 'authors.update',
                'updated_at' => '2022-04-04 07:25:25',
            ),
            58 =>
            array (
                'created_at' => '2022-04-04 07:25:39',
                'guard_name' => 'web',
                'id' => 59,
                'name' => 'authors.destroy',
                'updated_at' => '2022-04-04 07:25:39',
            ),
            59 =>
            array (
                'created_at' => '2022-04-04 07:54:34',
                'guard_name' => 'web',
                'id' => 60,
                'name' => 'publishers.index',
                'updated_at' => '2022-04-04 07:54:34',
            ),
            60 =>
            array (
                'created_at' => '2022-04-04 07:54:41',
                'guard_name' => 'web',
                'id' => 61,
                'name' => 'publishers.create',
                'updated_at' => '2022-04-04 07:54:41',
            ),
            61 =>
            array (
                'created_at' => '2022-04-04 07:54:48',
                'guard_name' => 'web',
                'id' => 62,
                'name' => 'publishers.store',
                'updated_at' => '2022-04-04 07:54:48',
            ),
            62 =>
            array (
                'created_at' => '2022-04-04 07:54:57',
                'guard_name' => 'web',
                'id' => 63,
                'name' => 'publishers.edit',
                'updated_at' => '2022-04-04 07:54:57',
            ),
            63 =>
            array (
                'created_at' => '2022-04-04 07:55:07',
                'guard_name' => 'web',
                'id' => 64,
                'name' => 'publishers.update',
                'updated_at' => '2022-04-04 07:55:07',
            ),
            64 =>
            array (
                'created_at' => '2022-04-04 07:55:17',
                'guard_name' => 'web',
                'id' => 65,
                'name' => 'publishers.destroy',
                'updated_at' => '2022-04-04 07:55:17',
            ),
            65 =>
            array (
                'created_at' => '2022-04-04 09:28:03',
                'guard_name' => 'web',
                'id' => 66,
                'name' => 'attributes.index',
                'updated_at' => '2022-04-04 09:28:03',
            ),
            66 =>
            array (
                'created_at' => '2022-04-04 09:28:11',
                'guard_name' => 'web',
                'id' => 67,
                'name' => 'attributes.create',
                'updated_at' => '2022-04-04 09:28:11',
            ),
            67 =>
            array (
                'created_at' => '2022-04-04 09:28:22',
                'guard_name' => 'web',
                'id' => 68,
                'name' => 'attributes.store',
                'updated_at' => '2022-04-04 09:28:22',
            ),
            68 =>
            array (
                'created_at' => '2022-04-04 09:28:30',
                'guard_name' => 'web',
                'id' => 69,
                'name' => 'attributes.edit',
                'updated_at' => '2022-04-04 09:28:30',
            ),
            69 =>
            array (
                'created_at' => '2022-04-04 09:28:51',
                'guard_name' => 'web',
                'id' => 70,
                'name' => 'attributes.update',
                'updated_at' => '2022-04-04 09:28:51',
            ),
            70 =>
            array (
                'created_at' => '2022-04-04 09:29:00',
                'guard_name' => 'web',
                'id' => 71,
                'name' => 'attributes.destroy',
                'updated_at' => '2022-04-04 09:29:00',
            ),
            71 =>
            array (
                'created_at' => '2022-04-04 09:45:52',
                'guard_name' => 'web',
                'id' => 72,
                'name' => 'books.index',
                'updated_at' => '2022-04-04 09:45:52',
            ),
            72 =>
            array (
                'created_at' => '2022-04-04 09:46:03',
                'guard_name' => 'web',
                'id' => 73,
                'name' => 'books.create',
                'updated_at' => '2022-04-04 09:46:03',
            ),
            73 =>
            array (
                'created_at' => '2022-04-04 09:46:27',
                'guard_name' => 'web',
                'id' => 74,
                'name' => 'books.store',
                'updated_at' => '2022-04-04 09:46:27',
            ),
            74 =>
            array (
                'created_at' => '2022-04-04 09:46:37',
                'guard_name' => 'web',
                'id' => 75,
                'name' => 'books.edit',
                'updated_at' => '2022-04-04 09:46:37',
            ),
            75 =>
            array (
                'created_at' => '2022-04-04 09:46:45',
                'guard_name' => 'web',
                'id' => 76,
                'name' => 'books.update',
                'updated_at' => '2022-04-04 09:46:45',
            ),
            76 =>
            array (
                'created_at' => '2022-04-04 09:47:07',
                'guard_name' => 'web',
                'id' => 77,
                'name' => 'books.destroy',
                'updated_at' => '2022-04-04 09:47:07',
            ),
            77 =>
            array (
                'created_at' => '2022-04-04 09:47:26',
                'guard_name' => 'web',
                'id' => 78,
                'name' => 'books.show',
                'updated_at' => '2022-04-04 09:47:26',
            ),
            78 =>
            array (
                'created_at' => '2022-04-05 07:08:58',
                'guard_name' => 'web',
                'id' => 79,
                'name' => 'articles.index',
                'updated_at' => '2022-04-05 07:08:58',
            ),
            79 =>
            array (
                'created_at' => '2022-04-05 07:09:07',
                'guard_name' => 'web',
                'id' => 80,
                'name' => 'articles.create',
                'updated_at' => '2022-04-05 07:09:07',
            ),
            80 =>
            array (
                'created_at' => '2022-04-05 07:09:15',
                'guard_name' => 'web',
                'id' => 81,
                'name' => 'articles.store',
                'updated_at' => '2022-04-05 07:09:15',
            ),
            81 =>
            array (
                'created_at' => '2022-04-05 07:09:27',
                'guard_name' => 'web',
                'id' => 82,
                'name' => 'articles.edit',
                'updated_at' => '2022-04-05 07:09:27',
            ),
            82 =>
            array (
                'created_at' => '2022-04-05 07:09:36',
                'guard_name' => 'web',
                'id' => 83,
                'name' => 'articles.update',
                'updated_at' => '2022-04-05 07:09:36',
            ),
            83 =>
            array (
                'created_at' => '2022-04-05 07:09:45',
                'guard_name' => 'web',
                'id' => 84,
                'name' => 'articles.destroy',
                'updated_at' => '2022-04-05 07:09:45',
            ),
            84 =>
            array (
                'created_at' => '2022-04-06 08:28:58',
                'guard_name' => 'web',
                'id' => 85,
                'name' => 'reviews.index',
                'updated_at' => '2022-04-06 08:28:58',
            ),
            85 =>
            array (
                'created_at' => '2022-04-06 08:29:30',
                'guard_name' => 'web',
                'id' => 86,
                'name' => 'reviews.create',
                'updated_at' => '2022-04-06 08:29:30',
            ),
            86 =>
            array (
                'created_at' => '2022-04-06 08:29:55',
                'guard_name' => 'web',
                'id' => 87,
                'name' => 'reviews.store',
                'updated_at' => '2022-04-06 08:29:55',
            ),
            87 =>
            array (
                'created_at' => '2022-04-06 08:30:06',
                'guard_name' => 'web',
                'id' => 88,
                'name' => 'reviews.edit',
                'updated_at' => '2022-04-06 08:30:06',
            ),
            88 =>
            array (
                'created_at' => '2022-04-06 08:30:17',
                'guard_name' => 'web',
                'id' => 89,
                'name' => 'reviews.update',
                'updated_at' => '2022-04-06 08:30:17',
            ),
            89 =>
            array (
                'created_at' => '2022-04-06 08:30:30',
                'guard_name' => 'web',
                'id' => 90,
                'name' => 'reviews.destroy',
                'updated_at' => '2022-04-06 08:30:30',
            ),
            90 =>
            array (
                'created_at' => '2022-04-06 08:31:13',
                'guard_name' => 'web',
                'id' => 91,
                'name' => 'reviews.show',
                'updated_at' => '2022-04-06 08:31:13',
            ),
            91 =>
            array (
                'created_at' => '2022-04-06 10:15:25',
                'guard_name' => 'web',
                'id' => 92,
                'name' => 'selections.index',
                'updated_at' => '2022-04-06 10:15:25',
            ),
            92 =>
            array (
                'created_at' => '2022-04-06 10:15:38',
                'guard_name' => 'web',
                'id' => 93,
                'name' => 'selections.create',
                'updated_at' => '2022-04-06 10:15:38',
            ),
            93 =>
            array (
                'created_at' => '2022-04-06 10:15:49',
                'guard_name' => 'web',
                'id' => 94,
                'name' => 'selections.store',
                'updated_at' => '2022-04-06 10:15:49',
            ),
            94 =>
            array (
                'created_at' => '2022-04-06 10:16:00',
                'guard_name' => 'web',
                'id' => 95,
                'name' => 'selections.edit',
                'updated_at' => '2022-04-06 10:16:00',
            ),
            95 =>
            array (
                'created_at' => '2022-04-06 10:16:16',
                'guard_name' => 'web',
                'id' => 96,
                'name' => 'selections.update',
                'updated_at' => '2022-04-06 10:16:16',
            ),
            96 =>
            array (
                'created_at' => '2022-04-06 10:16:38',
                'guard_name' => 'web',
                'id' => 97,
                'name' => 'selections.destroy',
                'updated_at' => '2022-04-06 11:22:20',
            ),
            97 =>
            array (
                'created_at' => '2022-04-06 10:16:48',
                'guard_name' => 'web',
                'id' => 98,
                'name' => 'selections.show',
                'updated_at' => '2022-04-06 10:16:48',
            ),
        ));


    }
}
