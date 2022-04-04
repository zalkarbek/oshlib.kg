<?php

namespace App\Repositories;

use App\Models\BookAttribute;

class BookAttributeRepository extends BaseRepository
{
    /**
    * Configure the Model
    **/
    public function model()
    {
        return BookAttribute::class;
    }
}
