<?php

namespace App\Repositories;

use App\Models\Publisher;

class PublisherRepository extends BaseRepository
{
    /**
    * Configure the Model
    **/
    public function model()
    {
        return Publisher::class;
    }
}
