<?php

namespace App\Http\Controllers;

use App\DataTables\BookDataTable;
use App\DataTables\SelectBookDataTable;
use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\User;
use App\Repositories\AttributeRepository;
use App\Repositories\AuthorRepository;
use App\Repositories\BookAttributeRepository;
use App\Repositories\BookRepository;
use App\Repositories\BookTagRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\FileRepository;
use App\Repositories\PublisherRepository;
use App\Repositories\TagRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
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

    public function __construct(
        BookRepository $bookRepository,
        CategoryRepository $categoryRepository,
        AuthorRepository $authorRepository,
        PublisherRepository $publisherRepository,
        AttributeRepository $attributeRepository,
        BookAttributeRepository $bookAttributeRepository,
        FileRepository $fileRepository,
        TagRepository $tagRepository,
        BookTagRepository $bookTagRepository)
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

        $releaseDate = null;
        $writingDate = null;

        return view('books.create', compact(['categories', 'authors',
            'publishers', 'attributes', 'tags', 'bookTags', 'releaseDate', 'writingDate']));
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

            $book = $this->bookRepository->create($input);
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
                foreach ($input['tags'] as $tag) {
                    $this->bookTagRepository->create(["book_id" => $book->id, "tag_id" => $tag]);
                }
            }

            splitPdf(storage_path('app/' . $file->path));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.book')]));

        return redirect(route('books.index'));
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

        $bookTags = $this->bookTagRepository->findByField('book_id', $book->id)->pluck('id');

        $releaseDate = Carbon::parse($book->release_date);
        $writingDate = Carbon::parse($book->writing_date);

        return view('books.edit', compact(['book', 'categories', 'authors',
            'publishers', 'attributes', 'tags', 'bookTags', 'releaseDate', 'writingDate']));
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
        try {
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                // unlink(storage_path($book->fileDetails->path));
                deleteDirWithFiles($book->fileDetails->id);
                $file = $this->saveBook($request);
                splitPdf(storage_path('app/' . $file->path));

                $input['file_id'] = $file->id;
            }

            $book = $this->bookRepository->update($input, $book->id);
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
                $this->bookTagRepository->findByField('book_id', $book->id)->each->delete();
                foreach ($input['tags'] as $tag) {
                    $this->bookTagRepository->create(["book_id" => $book->id, "tag_id" => $tag]);
                }
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.book')]));

        return redirect(route('books.index'));
    }

    private function saveBook(Request $request)
    {
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $name = $request->file('file')->getClientOriginalName();
            $mimeType = $request->file('file')->getClientMimeType();
            $fileSize = $request->file('file')->getSize();

            $file = $this->fileRepository->create(['name' => $name, 'mime_type' => $mimeType, 'file_size' => $fileSize, 'path' => '']);

            $path = $request->file('file')->store('books/' . $file->id);

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
