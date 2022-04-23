<?php


namespace App\Criteria\Category;


use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class OrderCategoryCriteria implements CriteriaInterface
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
                    return $model
                        ->join('books', 'books.category_id', '=', 'categories.id')
                        ->join('reviews', 'reviews.book_id', '=', 'books.id', 'left outer')
                        ->orderBy('reviews.rating', 'desc')
                        // ->orderBy('books.views', 'desc')
                        // ->orderBy('books.downloads', 'desc')
                        ->select('categories.*')
                        ->distinct()
                        ->groupBy(['categories.id', 'books.id', 'reviews.id']);
                default: return $model;
            }
        } else {
            return $model;
        }
    }
}
