<?php

namespace App\Http\Controllers\API;

use App\Helpers\PDFMerger\PDFMerger;
use App\Http\Controllers\AppBaseController;
use App\Models\Book;
use App\Repositories\BookRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ReviewRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class CategoryController
 * @package App\Http\Controllers\API
 */
class BookAPIController extends AppBaseController
{
    /** @var  BookRepository */
    private $bookRepository;
    /** @var  CategoryRepository */
    private $categoryRepository;
    /** @var ReviewRepository */
    private $reviewRepository;

    public function __construct(
        CategoryRepository $categoryRepo,
        BookRepository $bookRepo,
        ReviewRepository $reviewRepo)
    {
        $this->categoryRepository = $categoryRepo;
        $this->bookRepository = $bookRepo;
        $this->reviewRepository = $reviewRepo;
    }

    /**
     * Display a listing of the Book.
     * GET|HEAD /books
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->bookRepository->pushCriteria(new RequestCriteria($request));
            $this->bookRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $books = $this->bookRepository->all();

        return $this->sendResponse($books->toArray(), 'Books retrieved successfully');
    }

    /**
     * Display the specified Book.
     * GET|HEAD /books/{id}
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request)
    {
        /** @var Book $book */
        try {
            $this->bookRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $book = $this->bookRepository->findWithoutFail($id);
        if (empty($book)) {
            return $this->sendError('Book not found');
        }

        $book->views++;
        $book->save();

        return $this->sendResponse($book->toArray(), 'Book retrieved successfully');
    }

    /**
     * Store a newly created Book in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        try {
            $book = $this->bookRepository->create($input);
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $book->addMediaFromRequest('image')
                    ->toMediaCollection();
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($book->toArray(), __('lang.saved_successfully', ['operator' => __('lang.book')]));
    }

    /**
     * Update the specified Book in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $book = $this->bookRepository->findWithoutFail($id);

        if (empty($book)) {
            return $this->sendError('Book not found');
        }
        $input = $request->all();
        try {
            $book = $this->bookRepository->update($input, $id);

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $book->clearMediaCollection();
                $book->addMediaFromRequest('image')
                    ->toMediaCollection();
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($book->toArray(), __('lang.updated_successfully', ['operator' => __('lang.book')]));

    }

    /**
     * Remove the specified Book from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $book = $this->bookRepository->findWithoutFail($id);

        if (empty($book)) {
            return $this->sendError('Book not found');
        }

        $book = $this->bookRepository->delete($id);

        return $this->sendResponse($book, __('lang.deleted_successfully', ['operator' => __('lang.book')]));
    }

    /**
     * Display a listing of Book Review.
     * GET|HEAD /books/{id}/reviews
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reviews($id, Request $request)
    {
        try {
            $this->reviewRepository->pushCriteria(new RequestCriteria($request));
            $this->reviewRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $reviews = $this->reviewRepository->findByField('book_id', $id);

        return $this->sendResponse($reviews->toArray(), 'Reviews retrieved successfully');
    }

    public function byPage($bookId, $page, Request $request)
    {
        $output = exec('/usr/local/bin/node -v 2>&1');
        return "<pre>$output</pre>";
    }
}
