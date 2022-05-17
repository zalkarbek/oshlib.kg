<?php


namespace App\Criteria\Book;


use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class TagBooksCriteria implements CriteriaInterface
{
    private $request;
    private $tagId;

    public function __construct(Request $request, int $tagId)
    {
        $this->request = $request;
        $this->tagId = $tagId;
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
        return $model
            ->join('book_tags', 'book_tags.book_id', '=', 'books.id')
            ->where('book_tags.tag_id', '=', $this->tagId)
            ->select('books.*')
            ->groupBy(['books.id', 'book_tags.id']);
    }
}
