<?php

namespace App\Http\Controllers\API;

use App\Criteria\Book\FavoriteBooksCriteria;
use App\Criteria\Book\PopularBooksCriteria;
use App\Criteria\Book\RandomBooksCriteria;
use App\Http\Controllers\AppBaseController;
use App\Models\Book;
use App\Repositories\BookRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\FavoriteRepository;
use App\Repositories\FileRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\UserReadingRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Response;

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
    /** @var UserReadingRepository */
    private $userReadingRepository;
    /** @var FavoriteRepository */
    private $favoriteRepository;
    /** @var FileRepository */
    private $fileRepository;

    public function __construct(
        CategoryRepository $categoryRepo,
        BookRepository $bookRepo,
        ReviewRepository $reviewRepo,
        UserReadingRepository $userReadingRepo,
        FavoriteRepository $favoriteRepo,
        FileRepository $fileRepo)
    {
        $this->categoryRepository = $categoryRepo;
        $this->bookRepository = $bookRepo;
        $this->reviewRepository = $reviewRepo;
        $this->userReadingRepository = $userReadingRepo;
        $this->favoriteRepository = $favoriteRepo;
        $this->fileRepository = $fileRepo;
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
            $this->bookRepository->pushCriteria(new PopularBooksCriteria($request));
            $this->bookRepository->pushCriteria(new RandomBooksCriteria($request));
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

    /**
     * Display a listing of User Favorite Books.
     * GET|HEAD /books/my/favorites
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function favorites(Request $request)
    {
        try {
            $this->bookRepository->pushCriteria(new RequestCriteria($request));
            $this->bookRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->bookRepository->pushCriteria(new FavoriteBooksCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $books = $this->bookRepository->all();

        return $this->sendResponse($books->toArray(), 'Favorites retrieved successfully');
    }

    /**
     *
     * @param int $id
     * @param Request $request
     *
     */
    public function addToFavorites($id, Request $request)
    {
        $favorite = $this->favoriteRepository->findByField('book_id', $id)->first();

        if ($favorite) {
            return $this->sendError(400);
        }

        $favorite = $this->favoriteRepository->create(['book_id' => $id, 'user_id' => auth()->id()]);

        return $this->sendResponse($favorite->book, 'Added to favorites successfully');
    }

    /**
     *
     * @param int $id
     * @param Request $request
     *
     */
    public function removeFromFavorites($id, Request $request)
    {
        $favorite = $this->favoriteRepository->findByField('book_id', $id)->first();

        if (empty($favorite)) {
            return $this->sendError(404);
        }

        $favorite->delete();

        return $this->sendResponse([], 'Successfully removed from favorites');
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function wantToRead($id, Request $request)
    {
        return $this->changeReadingStatus($id, 'want_to_read');
    }

    public function reading($id, Request $request)
    {
        $this->changeReadingStatus($id, 'reading');
    }

    public function read($id, Request $request)
    {
        $this->changeReadingStatus($id, 'read');
    }

    private function changeReadingStatus($bookId, $newStatus)
    {
        $userId = auth()->id();
        try {
            $readingStatus = $this->userReadingRepository->findByField('book_id', $bookId)->where('user_id', '=', auth()->id())->first();
            if ($readingStatus) {
                $this->userReadingRepository->update(['user_id' => $userId, 'book_id' => $bookId, 'status' => $newStatus], $readingStatus->id);
            } else {
                $this->userReadingRepository->create(['user_id' => $userId, 'book_id' => $bookId, 'status' => $newStatus]);
            }
        } catch (ValidatorException $e) {
            return $this->sendError('validator error', 405);
        }

        return $this->sendSuccess('successfully changed');
    }

}
