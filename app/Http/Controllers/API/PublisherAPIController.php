<?php


namespace App\Http\Controllers\API;

use App\Criteria\Book\OrderBooksCriteria;
use App\Http\Controllers\AppBaseController;
use App\Models\Publisher;
use App\Repositories\PublisherRepository;
use App\Repositories\BookRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class PublisherAPIController
 * @package App\Http\Controllers\API
 */
class PublisherAPIController extends AppBaseController
{
    /** @var  PublisherRepository */
    private $publisherRepository;
    /** @var  BookRepository */
    private $bookRepository;

    public function __construct(PublisherRepository $publisherRepo, BookRepository $bookRepo)
    {
        $this->publisherRepository = $publisherRepo;
        $this->bookRepository = $bookRepo;
    }

    /**
     * Display a listing of the Publisher.
     * GET|HEAD /publishers
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->publisherRepository->pushCriteria(new RequestCriteria($request));
            $this->publisherRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $publishers = $this->publisherRepository->all();

        return $this->sendResponse($publishers->toArray(), 'Publishers retrieved successfully');
    }

    /**
     * Display the specified Author.
     * GET|HEAD /authors/{id}
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request)
    {
        try {
            $this->publisherRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $publisher = $this->publisherRepository->findWithoutFail($id);

        if (empty($publisher)) {
            return $this->sendError('Publisher not found');
        }

        return $this->sendResponse($publisher->toArray(), 'Publisher retrieved successfully');
    }

    /**
     * Display a listing of Publisher Book.
     * GET|HEAD /publishers/{id}/books
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function books($id, Request $request)
    {
        try {
            $this->bookRepository->pushCriteria(new RequestCriteria($request));
            $this->bookRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->bookRepository->pushCriteria(new OrderBooksCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $books = $this->bookRepository->findByField('publisher_id', $id);

        return $this->sendResponse($books->toArray(), 'Books retrieved successfully');
    }
}
