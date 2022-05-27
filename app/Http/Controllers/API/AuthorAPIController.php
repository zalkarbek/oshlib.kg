<?php


namespace App\Http\Controllers\API;

use App\Criteria\Book\OrderBooksCriteria;
use App\Http\Controllers\AppBaseController;
use App\Models\Author;
use App\Repositories\AuthorRepository;
use App\Repositories\BookRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class AuthorAPIController
 * @package App\Http\Controllers\API
 */
class AuthorAPIController extends AppBaseController
{
    /** @var  AuthorRepository */
    private $authorRepository;
    /** @var  BookRepository */
    private $bookRepository;

    public function __construct(AuthorRepository $authorRepo, BookRepository $bookRepo)
    {
        $this->authorRepository = $authorRepo;
        $this->bookRepository = $bookRepo;
    }

    /**
     * Display a listing of the Author.
     * GET|HEAD /authors
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->authorRepository->pushCriteria(new RequestCriteria($request));
            $this->authorRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $authors = $this->authorRepository->all();

        return $this->sendResponse($authors->toArray(), 'Authors retrieved successfully');
    }

    /**
     * Display the specified Author.
     * GET|HEAD /authors/{id}
     *
     * @param Author $author
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Author $author, Request $request)
    {
        try {
            $this->authorRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $author = $this->authorRepository->findWithoutFail($author->id);

        if (empty($author)) {
            return $this->sendError('Author not found');
        }

        return $this->sendResponse($author->toArray(), 'Author retrieved successfully');
    }

    /**
     * Display a listing of Author Book.
     * GET|HEAD /author/{id}/books
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function books($id, Request $request)
    {
        removeAuthorKey($request);

        try {
            $this->bookRepository->pushCriteria(new RequestCriteria($request));
            $this->bookRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->bookRepository->pushCriteria(new OrderBooksCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $books = $this->bookRepository->findByField('author_id', $id);

        return $this->sendResponse($books->toArray(), 'Books retrieved successfully');
    }
}
