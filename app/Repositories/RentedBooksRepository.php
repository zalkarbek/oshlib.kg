<?php


namespace App\Repositories;

use App\Models\RentedBooks;

class RentedBooksRepository extends BaseRepository
{

    public function model()
    {
        return RentedBooks::class;
    }
}
