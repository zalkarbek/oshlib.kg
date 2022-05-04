<?php

namespace App\Repositories;

use App\Models\ArticleCategory;

class ArticleCategoryRepository extends BaseRepository
{
    /**
    * Configure the Model
    **/
    public function model()
    {
        return ArticleCategory::class;
    }
}
