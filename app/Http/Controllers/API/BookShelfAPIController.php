<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\AppBaseController;
use App\Models\BookShelf;
use App\Models\UserBookShelf;
use App\Repositories\BookShelfRepository;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class BookShelfAPIController extends AppBaseController
{
    /** @var BookShelfRepository */
    private $bookShelfRepository;

    public function __construct(BookShelfRepository $bookShelfRepository)
    {
        $this->bookShelfRepository = $bookShelfRepository;
    }

    public function index(Request $request)
    {
        $bookShelves = $request->user()->bookShelves()->with('books')->groupBy(['id', 'user_id'])->get();

        return $this->sendResponse($bookShelves, 'Bookshelves retrieved successfully');
    }

    /**
     * Display the specified Book.
     * GET|HEAD /selections/{id}
     *
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request)
    {
        try {
            $this->bookShelfRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        $bookShelf = $this->bookShelfRepository->findWithoutFail($id);
        if (empty($bookShelf)) {
            return $this->sendError('BookShelf not found');
        }

        return $this->sendResponse($bookShelf, 'BookShelf retrieved successfully');
    }

    public function store(Request $request)
    {
        $name = $request->input('name');

        $bookShelf = BookShelf::where('name', $name)
            ->where('user_id', '=', auth()->id())
            ->first();
        if ($bookShelf) {
            return $this->sendError('Book shelf with this name already exists', 405);
        }

        $bookShelf = new BookShelf;
        $bookShelf->name = $name;
        $bookShelf->user_id = auth()->id();
        $bookShelf->is_public = $request->input('is_public', 1);
        $bookShelf->save();

        if ($request->hasFile('image')) {
            try {
                $bookShelf->addMediaFromRequest('image')
                    ->toMediaCollection();
            } catch (\Exception $e) {
                return $this->sendError('Couldn`t save image', 405);
            }
        }

        $books = $request->input('books');
        if ($books) {
            $this->addBooksToShelf($books, $bookShelf->id);
        }

        return $this->sendResponse($bookShelf,'created successfully');
    }

    /**
     * @param BookShelf $bookShelf
     * @param Request $request
     * @return mixed
     */
    public function update(BookShelf $bookShelf, Request $request)
    {
        if ($request->has('name')) {
            $bookShelf->name = $request->input('name');
        }
        if ($request->has('is_public')) {
            $bookShelf->is_public = $request->input('is_public');
        }

        if ($bookShelf->isDirty()) {
            $bookShelf->save();
        }

        if ($request->hasFile('image')) {
            try {
                $bookShelf->clearMediaCollection();
                $bookShelf->addMediaFromRequest('image')
                    ->toMediaCollection();
            } catch (\Exception $e) {
                return $this->sendError('Couldn`t save image', 405);
            }
        }

        $books = $request->input('books');
        if ($books) {
            $this->addBooksToShelf($books, $bookShelf->id);
        }

        return $this->sendResponse($bookShelf,'updated successfully');
    }

    public function addBooksToShelf($books, $shelfId)
    {
        foreach ($books as $book) {
            $userBookShelf = UserBookShelf::where([
                ['book_id', '=', $book],
                ['book_shelf_id', '=', $shelfId]
            ])->exists();
            if (!$userBookShelf) {
                $userBookShelf = new UserBookShelf;
                $userBookShelf->book_id = $book;
                $userBookShelf->book_shelf_id = $shelfId;
                $userBookShelf->save();
            }
        }
    }

    /**
     * @param int $id
     * @param Request $request
     */
    public function deleteBooksFromShelf($id, Request $request)
    {
        $books = $request->input('books');
        if ($books) {
            foreach ($books as $book) {
                UserBookShelf::where([
                    ['book_id', '=', $book],
                    ['book_shelf_id', '=', $id]
                ])->delete();
            }
        }

        return $this->sendResponse(BookShelf::find($id), 'success');
    }

    /**
     * @param int $id
     * @param int $bookId
     * @param Request $request
     */
    public function deleteBookFromShelf($id, $bookId, Request $request)
    {
        UserBookShelf::where([
            ['book_id', '=', $bookId],
            ['book_shelf_id', '=', $id]
        ])->delete();

        return $this->sendResponse(BookShelf::find($id), 'success');
    }



    /**
     * @param BookShelf $bookShelf
     * @throws \Throwable
     */
    public function destroy(BookShelf $bookShelf)
    {
        $bookShelf->deleteOrFail();

        return $this->sendSuccess('deleted successfully');
    }
}
