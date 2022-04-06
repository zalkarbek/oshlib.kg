<?php

namespace App\Repositories;

use App\Models\BookSelection;

class BookSelectionRepository extends BaseRepository
{
    /**
    * Configure the Model
    **/
    public function model()
    {
        return BookSelection::class;
    }
}
