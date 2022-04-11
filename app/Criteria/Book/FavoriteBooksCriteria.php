<?php


namespace App\Criteria\Book;

use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;

class FavoriteBooksCriteria implements CriteriaInterface
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if (auth()->check()) {
            return $model->join('favorites', 'favorites.book_id', '=', 'books.id')
                ->where('favorites.user_id', auth()->id())
                ->orderBy('books.updated_at', 'asc')
                ->select('books.*')
                ->groupBy('books.id');
        } else {
            return $model;
        }
    }
}
