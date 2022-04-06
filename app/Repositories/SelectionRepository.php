<?php

namespace App\Repositories;

use App\Models\Selection;

class SelectionRepository extends BaseRepository
{
    /**
    * Configure the Model
    **/
    public function model()
    {
        return Selection::class;
    }
}
