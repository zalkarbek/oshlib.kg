<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\AppBaseController;
use App\Models\BookShelf;
use App\Models\UserBookShelf;
use App\Repositories\BookShelfRepository;
use Illuminate\Http\Request;

class BookShelfAPIController extends AppBaseController
{
    /** @var BookShelfRepository */
    private $bookShelfRepository;

    public function index(Request $request)
    {
        $bookShelves = $request->user()->bookShelves()->with('books')->groupBy(['id', 'user_id'])->get();

        return $this->sendResponse($bookShelves, 'Bookshelves retrieved successfully');
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        $bookShelf = $request->user()->bookShelves()->where('name', $name)->first();
        if ($bookShelf) {
            return $this->sendError('Book shelf with this name already exists', 405);
        }

        $bookShelf = new BookShelf;
        $bookShelf->name = $name;
        $bookShelf->save();

        $books = $request->input('books');
        if ($books) {
            $this->addBooksToShelf($books, $bookShelf->id);
        }

        return $this->sendSuccess('created successfully');
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
            $bookShelf->save();
        }

        $books = $request->input('books');
        if ($books) {
            $this->addBooksToShelf($books, $bookShelf->id);
        }

        return $this->sendSuccess('updated successfully');
    }

    public function addBooksToShelf($books, $shelfId)
    {
        foreach ($books as $book) {
            $userBookShelf = UserBookShelf::where([
                ['user_id', '=', auth()->id()],
                ['book_id', '=', $book],
                ['book_shelf_id', '=', $shelfId]
            ])->exists();
            if (!$userBookShelf) {
                $userBookShelf = new UserBookShelf;
                $userBookShelf->user_id = auth()->id();
                $userBookShelf->book_id = $book;
                $userBookShelf->book_shelf_id = $shelfId;
                $userBookShelf->save();
            }
        }
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
