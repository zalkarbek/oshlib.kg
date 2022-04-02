<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleRepository extends BaseRepository
{
    /**
    * Configure the Model
    **/
    public function model()
    {
        return Article::class;
    }
}