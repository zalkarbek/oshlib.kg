<?php


namespace App\Criteria\Book;


use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class OrderBooksCriteria implements CriteriaInterface
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
                        ->select('books.*', \DB::raw("(SELECT sum(reviews.rating) FROM reviews WHERE book_id = books.id) as rating_sum"))
                        ->orderBy('rating_sum', 'desc')
                        ->orderBy('views', 'desc')
                        ->orderBy('downloads', 'desc');
                case 'random': return $model->inRandomOrder();
                case 'read': return $model
                    ->select('books.*', \DB::raw("(SELECT count(*) FROM user_readings WHERE book_id = books.id AND status = 'read') as count_read"))
                    ->with('userReadings')
                    ->orderBy('count_read', 'desc');
                default: return $model;
            }
        } else {
            return $model;
        }
    }
}
