<?php


namespace App\Criteria\Book;


use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class ReadStatusFilterCriteria implements CriteriaInterface
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
        if ($this->request->has('read_status')) {
            $status = $this->request->get('read_status');
            switch ($status) {
                case 'all':
                    return $model
                        ->join('user_readings', 'user_readings.book_id', '=', 'books.id')
                        ->where('user_readings.user_id', '=', auth('sanctum')->id())
                        ->where('user_readings.status', 'not', 'none')
                        ->select('books.*')
                        ->groupBy(['books.id', 'user_readings.id']);
                default: return $model;
            }
        } else {
            return $model;
        }
    }
}
