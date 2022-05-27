<?php

namespace App\Repositories;

use App\Models\BookAuthor;

class BookAuthorRepository extends BaseRepository
{
    /**
    * Configure the Model
    **/
    public function model()
    {
        return BookAuthor::class;
    }
}
