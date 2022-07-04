<?php

namespace App\Http\Controllers;

use App\DataTables\BookDataTable;
use App\DataTables\SelectBookDataTable;
use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Tag;
use App\Repositories\AttributeRepository;
use App\Repositories\AuthorRepository;
use App\Repositories\BookAttributeRepository;
use App\Repositories\BookRepository;
use App\Repositories\BookTagRepository;
use App\Repositories\BookAuthorRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\FileRepository;
use App\Repositories\PublisherRepository;
use App\Repositories\TagRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Prettus\Validator\Exceptions\ValidatorException;
use Flash;
use Response;

class BookController extends AppBaseController
{
    /** @var BookRepository */
    private $bookRepository;
    /** @var CategoryRepository */
    private $categoryRepository;
    /** @var AuthorRepository */
    private $authorRepository;
    /** @var PublisherRepository */
    private $publisherRepository;
    /** @var AttributeRepository */
    private $attributeRepository;
    /** @var BookAttributeRepository */
    private $bookAttributeRepository;
    /** @var FileRepository */
    private $fileRepository;
    /** @var TagRepository */
    private $tagRepository;
    /** @var BookTagRepository */
    private $bookTagRepository;
    /** @var BookAuthorRepository */
    private $bookAuthorRepository;

    public function __construct(
        BookRepository $bookRepository,
        CategoryRepository $categoryRepository,
        AuthorRepository $authorRepository,
        PublisherRepository $publisherRepository,
        AttributeRepository $attributeRepository,
        BookAttributeRepository $bookAttributeRepository,
        FileRepository $fileRepository,
        TagRepository $tagRepository,
        BookTagRepository $bookTagRepository,
        BookAuthorRepository $bookAuthorRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->categoryRepository = $categoryRepository;
        $this->authorRepository = $authorRepository;
        $this->publisherRepository = $publisherRepository;
        $this->attributeRepository = $attributeRepository;
        $this->bookAttributeRepository = $bookAttributeRepository;
        $this->fileRepository = $fileRepository;
        $this->tagRepository = $tagRepository;
        $this->bookTagRepository = $bookTagRepository;
        $this->bookAuthorRepository = $bookAuthorRepository;
    }

    public function authorTest()
    {
        $book = $this->bookRepository->first();

        return $book->nauthor();
    }

    /**
     * Display a listing of the resource.
     *
     * @param BookDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(BookDataTable $dataTable)
    {
        return $dataTable->render('books.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @param SelectBookDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function selectBooksTable(SelectBookDataTable $dataTable)
    {
        return $dataTable->render('books.selectTable');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->categoryRepository->all()->pluck('name', 'id');
        $authors = $this->authorRepository->all()->pluck('name', 'id');
        $publishers = $this->publisherRepository->all()->pluck('name', 'id');
        $attributes = $this->attributeRepository->all()->pluck('title', 'id');
        $tags = $this->tagRepository->all()->pluck('name', 'id');

        $bookTags = [];
        $bookAuthors = [];

        $releaseDate = null;
        $writingDate = null;

        return view('books.create', compact(['categories', 'authors',
            'publishers', 'attributes', 'tags',
            'bookTags', 'releaseDate', 'writingDate', 'bookAuthors',
            ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateBookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBookRequest $request)
    {
        $input = $request->all();

        try {
            $file = $this->saveBook($request);

            $input['file_id'] = $file->id;
            $input['name'] = $input['book_name'];
            $input['available_for_rent'] = filter_var($input['available_for_rent'] ?? 'false', FILTER_VALIDATE_BOOLEAN);

            $book = $this->bookRepository->create($input);
            $this->addAuthorsToBook($input['authors'], $book->id);
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $book->addMediaFromRequest('image')
                    ->toMediaCollection();
            }

            if ($request->has('attributes')) {
                $values = $input['attributes_values'];
                foreach ($input['attributes'] as $key => $attribute) {
                    $this->bookAttributeRepository->create(["book_id" => $book->id, "attribute_id" => $attribute, "value" => $values[$key]]);
                }
            }

            if ($request->has('tags')) {
                $this->addTagsToBook($input['tags'], $book->id);
            }

            splitPdf(Storage::disk('diskD')->path($file->path));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.book')]));

        return redirect(route('books.index'));
    }

    public function storeFromJson(Request $request)
    {
        $items = json_decode($request->input('json'));
        foreach ($items as $item) {
            // --------------------------------------------------
            $f = new \Illuminate\Http\File($item->book_file);
            $file = $this->fileRepository->create([
                'name' => $f->getBasename(),
                'mime_type' => $f->getMimeType(),
                'file_size' => $f->getSize(),
                'path' => ''
            ]);
            $path = Storage::disk('diskD')->putFile('elkitep/books/' . $file->id, $f);
            $file->path = $path;
            $file->save();
            // --------------------------------------------------

            $tags = [];
            $this->fillWith($item->tags, function($item) {
                return $this->tagRepository->findByField('name', $item)->first();
            }, function($item) {
                return $this->tagRepository->create(['name' => $item, 'type' => 'genre', 'description' => '']);
            }, $tags);

            $authors = [];
            $this->fillWith($item->authors, function($item) {
                return $this->authorRepository->findByField('name', $item)->first();
            }, function($item) {
                return $this->authorRepository->create(['name' => $item, 'description' => '']);
            }, $authors);

            $category = $this->categoryRepository->findByField('name', $item->category)->first();
            if (!$category) {
                $category = $this->categoryRepository->create(['name' => $item->category]);
            }

            $publisher = $this->publisherRepository->findByField('name', $item->publisher)->first();
            if (!$publisher) {
                $publisher = $this->publisherRepository->create(['name' => $item->publisher, 'description' => '']);
            }

            $input = [
                'file_id' => $file->id,
                'name' => $item->book_name,
                'description' => $item->description ?? '',
                'category_id' => $category->id,
                'publisher_id' => $publisher->id,
                'page_count' => $item->page_count,
                'release_date' => $item->release_date ?? null,
                'writing_date' => $item->writing_date ?? null,
                'has_variants' => $item->has_variants ?? 'all',
                'available_for_rent' => filter_var($item->available_for_rent ?? 'true', FILTER_VALIDATE_BOOLEAN)
            ];

            $book = $this->bookRepository->create($input);
            $this->addAuthorsToBook($authors, $book->id);
            $this->addTagsToBook($tags, $book->id);

            splitPdf(Storage::disk('diskD')->path($file->path));
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.book')]));

        return redirect(route('books.index'));
    }

    private function fillWith($items, $hasItem, $create, &$fillArr)
    {
        foreach ($items as $item) {
            $ii = $hasItem($item);
            if ($ii) {
                $fillArr[] = $ii->id;
            } else {
                $fillArr[] = $create($item)->id;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return view('books.show', compact(['book']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        $categories = $this->categoryRepository->all()->pluck('name', 'id');
        $authors = $this->authorRepository->all()->pluck('name', 'id');
        $publishers = $this->publisherRepository->all()->pluck('name', 'id');
        $attributes = $this->attributeRepository->all()->pluck('title', 'id');
        $tags = $this->tagRepository->all()->pluck('name', 'id');

        $bookTags = Tag::join('book_tags', 'book_tags.tag_id', '=', 'tags.id')->where('book_tags.book_id', '=', $book->id)->pluck('tags.id');
        $bookAuthors = Author::join('book_authors', 'book_authors.author_id', '=', 'authors.id')
            ->where('book_authors.book_id', '=', $book->id)->pluck('authors.id');

        $releaseDate = Carbon::parse($book->release_date);
        $writingDate = Carbon::parse($book->writing_date);

        return view('books.edit', compact(['book', 'categories', 'authors', 'publishers',
            'attributes', 'tags', 'bookTags', 'releaseDate', 'writingDate', 'bookAuthors']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateBookRequest  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $input = $request->all();
        $input['available_for_rent'] = filter_var($input['available_for_rent'] ?? 'false', FILTER_VALIDATE_BOOLEAN);
        $input['name'] = $input['book_name'];
        try {
            if ($request->hasFile('book_file') && $request->file('book_file')->isValid()) {
                // unlink(storage_path($book->fileDetails->path));
                deleteDirWithFiles(Storage::disk('diskD')->path($book->fileDetails->path));
                $file = $this->saveBook($request);
                splitPdf(Storage::disk('diskD')->path($file->path));

                $input['file_id'] = $file->id;
            }

            $book = $this->bookRepository->update($input, $book->id);
            $this->addAuthorsToBook($input['authors'], $book->id);
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $book->clearMediaCollection();
                $book->addMediaFromRequest('image')
                    ->toMediaCollection();
            }

            if ($request->has('attributes')) {
                $this->bookAttributeRepository->findByField('book_id', $book->id)->each->delete();
                $values = $input['attributes_values'];
                foreach ($input['attributes'] as $key => $attribute) {
                    $this->bookAttributeRepository->create(["book_id" => $book->id, "attribute_id" => $attribute, "value" => $values[$key]]);
                }
            }

            if ($request->has('tags')) {
                $this->addTagsToBook($input['tags'], $book->id);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.book')]));

        return redirect(route('books.index'));
    }

    private function addAuthorsToBook($authors, $bookId)
    {
        $this->bookAuthorRepository->findByField('book_id', $bookId)->each->delete();
        foreach ($authors as $author) {
            $this->bookAuthorRepository->create(["book_id" => $bookId, "author_id" => $author]);
        }
    }

    private function addTagsToBook($tags, $bookId)
    {
        $this->bookTagRepository->findByField('book_id', $bookId)->each->delete();
        foreach ($tags as $tag) {
            $this->bookTagRepository->create(["book_id" => $bookId, "tag_id" => $tag]);
        }
    }

    private function saveBook(Request $request)
    {
        if ($request->hasFile('book_file') && $request->file('book_file')->isValid()) {
            $name = $request->file('book_file')->getClientOriginalName();
            $mimeType = $request->file('book_file')->getClientMimeType();
            $fileSize = $request->file('book_file')->getSize();

            $file = $this->fileRepository->create(['name' => $name, 'mime_type' => $mimeType, 'file_size' => $fileSize, 'path' => '']);

            $path = Storage::disk('diskD')->putFile('elkitep/books/' . $file->id, $request->file('book_file'));
            // $path = $request->file('file')->store('books/' . $file->id);

            $file->path = $path;
            $file->save();

            return $file;
        }

        return null;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->deleteWithFiles();

        return redirect(route('books.index'));
    }

}
