<?php


namespace App\Repositories;


use App\Models\Author;

class AuthorRepository extends BaseRepository
{

    public function model()
    {
        return Author::class;
    }
}
