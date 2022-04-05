<?php

namespace App\Repositories;

use App\Models\BookTag;

class BookTagRepository extends BaseRepository
{
    /**
    * Configure the Model
    **/
    public function model()
    {
        return BookTag::class;
    }
}
