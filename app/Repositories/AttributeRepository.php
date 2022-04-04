<?php

namespace App\Repositories;

use App\Models\Attribute;

class AttributeRepository extends BaseRepository
{
    /**
    * Configure the Model
    **/
    public function model()
    {
        return Attribute::class;
    }
}
