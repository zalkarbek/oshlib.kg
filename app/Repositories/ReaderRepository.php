<?php


namespace App\Repositories;


use App\Models\Reader;

class ReaderRepository extends BaseRepository
{

    public function model()
    {
        return Reader::class;
    }
}
