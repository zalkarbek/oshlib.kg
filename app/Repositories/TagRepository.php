<?php


namespace App\Repositories;

use App\Models\Tag;

class TagRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Tag::class;
    }
}
