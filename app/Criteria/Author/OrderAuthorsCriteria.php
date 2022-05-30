<?php


namespace App\Criteria\Author;


use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class OrderAuthorsCriteria implements CriteriaInterface
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
        if ($this->request->has('order')) {
            $order = $this->request->get('order');
            switch ($order) {
                case 'popular':
                    $rawSql = "(SELECT (select sum((select (SELECT sum(reviews.rating) FROM reviews WHERE book_id = books.id) from books where books.id = book_authors.book_id))) FROM `book_authors` WHERE book_authors.author_id = authors.id) as rating_sum";
                    return $model
                        ->select('authors.*', \DB::raw($rawSql))
                        ->orderBy('rating_sum', 'desc');
                default: return $model;
            }
        } else {
            return $model;
        }
    }
}
