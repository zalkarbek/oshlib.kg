<?php


namespace App\Repositories;

use App\Models\UserReading;

class UserReadingRepository extends BaseRepository
{
    public function model()
    {
        return UserReading::class;
    }
}
