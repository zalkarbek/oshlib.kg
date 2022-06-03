<?php

namespace App\Http\Controllers\API;

use App\Criteria\Book\FavoriteBooksCriteria;
use App\Criteria\Book\OrderBooksCriteria;
use App\Criteria\Book\ReadStatusFilterCriteria;
use App\Http\Controllers\AppBaseController;
use App\Models\Book;
use App\Models\BookShelf;
use App\Models\Favorite;
use App\Models\Review;
use App\Models\UserBookShelf;
use App\Repositories\BookRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\FavoriteRepository;
use App\Repositories\FileRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\UserReadingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Laravel\Sanctum\PersonalAccessToken;
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
        removeAuthorKey($request);

        try {
            $this->bookRepository->pushCriteria(new RequestCriteria($request));
            $this->bookRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->bookRepository->pushCriteria(new OrderBooksCriteria($request));
            $this->bookRepository->pushCriteria(new ReadStatusFilterCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $books = $this->bookRepository->all();

        return $this->sendResponse($books, 'Books retrieved successfully');
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
        removeAuthorKey($request);

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
        $this->removeAuthorKey($request);

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
        $favorite = Favorite::where('book_id', '=', $id)->where('user_id', '=', auth()->id())->first();

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
        $favorite = Favorite::where('book_id', '=', $id)->where('user_id', '=', auth()->id())->first();

        if (!$favorite) {
            return $this->sendError(404);
        }

        $book = $favorite->book;
        $favorite->delete();

        return $this->sendResponse($book, 'Successfully removed from favorites');
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

    public function deleteReadStatus($id)
    {
        $this->changeReadingStatus($id, 'none');
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

    /**
     * @param int $id
     * @param Request $request
     */
    public function deleteFromMyBooks($id, Request $request)
    {
        UserBookShelf::join('book_shelves', 'user_book_shelves.book_shelf_id', '=', 'book_shelves.id')
            ->where('book_id', '=', $id)
            ->where('book_shelves.user_id', '=', auth()->id())
            ->delete();

        $this->changeReadingStatus($id, 'none');

        return $this->sendResponse(BookShelf::find($id), 'success');
    }

    /**
     * View a Book page.
     * GET|HEAD /books/{id}/pages/{page}
     *
     * @param int $bookId
     * @param int $page
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function byPage($bookId, $page, Request $request)
    {
        $input = $request->all();
        if (isset($input['api_token'])) {
            $token = PersonalAccessToken::findToken($input['api_token']);

            if (!$token) return $this->sendError('token not found', 401);
        } else {
            return $this->sendError('', 401);
        }

        $book = $this->bookRepository->findWithoutFail($bookId);

        if (empty($book)) {
            return $this->sendError(404);
        }

        $path = Storage::disk('diskD')->path("elkitep/books/" . $book->fileDetails->id . "/pages/$page.pdf");
        // $path = storage_path("app/books/" . $book->fileDetails->id . "/pages/$page.pdf");

        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $path . '"'
        ]);
    }

    /**
     * Preview of a Book.
     * GET|HEAD /books/{id}/preview
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bookPreview($id, Request $request)
    {
        $book = $this->bookRepository->findWithoutFail($id);

        if (empty($book)) {
            return $this->sendError(404);
        }

        [$name, $ext] = explode('.', $book->fileDetails->path, 2);
<<<<<<< HEAD
        $path = Storage::disk('diskD')->path("elkitep/books/" . $book->fileDetails->path . "-excerpt." . $ext);
=======
        $path = Storage::disk('diskD')->path("elkitep/" . $book->fileDetails->path . "-excerpt." . $ext);
>>>>>>> 28056879255b963f083b2842d75fc481cfbf85d8
        // $path = storage_path("app/" . $book->fileDetails->path . "-excerpt." . $ext);

        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $path . '"'
        ]);
    }

    /**
     * Preview of a Book.
     * GET|HEAD /books/{id}/preview
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function bookFile($id, Request $request)
    {
        $input = $request->all();
        if (isset($input['api_token'])) {
            $token = PersonalAccessToken::findToken($input['api_token']);

            if (!$token) return $this->sendError('token not found', 401);
        } else {
            return $this->sendError('', 401);
        }

        $book = $this->bookRepository->findWithoutFail($id);

        if (empty($book)) {
            return $this->sendError(404);
        }

        $path = storage_path("app/" . $book->fileDetails->path);

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $path . '"'
        ];

        if ($request->has('action') && $request->input('action') === 'download') {
            return response()->download($path, basename($path), $headers);
        }

        return response()->make(file_get_contents($path), 200, $headers);
    }

    /**
     *
     * @param int $id
     * @param Request $request
     *
     */
    public function rate($id, Request $request)
    {
        $input = $request->all();
        $review = Review::where('book_id', '=', $id)->where('user_id', '=', auth()->id())->first();

        if ($review) {
            $review = $this->reviewRepository->update($input, $review->id);
        } else {
            $input['user_id'] = auth()->id();
            $input['book_id'] = $id;
            $review = $this->reviewRepository->create($input);
        }

        $review->user;

        return $this->sendResponse($review, 'Review added successfully');
    }

    /**
     *
     * @param int $id
     * @param Request $request
     *
     */
    public function deleteRate($id, Request $request)
    {
        $review = Review::where('book_id', '=', $id)->where('user_id', '=', auth()->id())->first();

        if (!$review) {
            return $this->sendError(404);
        }

        $review->delete();

        return $this->sendResponse([], 'Successfully removed from reviews');
    }

    /**
     *
     * @param int $id
     * @param Request $request
     *
     */
    public function myReview($id, Request $request)
    {
        $review = Review::where('book_id', '=', $id)->where('user_id', '=', auth()->id())->first();

        if (!$review) {
            return $this->sendError(404);
        }

        return $this->sendResponse($review, 'Successfully');
    }
}
