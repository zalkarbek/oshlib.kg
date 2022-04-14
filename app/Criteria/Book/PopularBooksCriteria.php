<?php


namespace App\Criteria\Book;


use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class PopularBooksCriteria implements CriteriaInterface
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
        if ($this->request->has('orderPopular')) {
            return $model
                ->join('reviews', 'reviews.book_id', '=', 'books.id', 'left outer')
                ->orderBy('reviews.rating', 'desc')
                ->orderBy('views', 'desc')
                ->orderBy('downloads', 'desc')
                ->select('books.*')
                ->groupBy(['books.id', 'reviews.id']);
        } else {
            return $model;
        }
    }
}
