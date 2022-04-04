<?php

namespace App\Repositories;

use App\Models\File;

class FileRepository extends BaseRepository
{
    /**
    * Configure the Model
    **/
    public function model()
    {
        return File::class;
    }
}
