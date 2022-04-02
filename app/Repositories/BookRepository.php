<?php


namespace App\Repositories;

use App\Models\Book;

class BookRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Book::class;
    }
}
