<?php

namespace App\Http\Controllers;

use App\DataTables\BookDataTable;
use App\Models\Book;
use App\Repositories\AttributeRepository;
use App\Repositories\AuthorRepository;
use App\Repositories\BookAttributeRepository;
use App\Repositories\BookRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\FileRepository;
use App\Repositories\PublisherRepository;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;
use Flash;

class BookController extends Controller
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

    public function __construct(
        BookRepository $bookRepository,
        CategoryRepository $categoryRepository,
        AuthorRepository $authorRepository,
        PublisherRepository $publisherRepository,
        AttributeRepository $attributeRepository,
        BookAttributeRepository $bookAttributeRepository,
        FileRepository $fileRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->categoryRepository = $categoryRepository;
        $this->authorRepository = $authorRepository;
        $this->publisherRepository = $publisherRepository;
        $this->attributeRepository = $attributeRepository;
        $this->bookAttributeRepository = $bookAttributeRepository;
        $this->fileRepository = $fileRepository;
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

        return view('books.create', compact(['categories', 'authors', 'publishers', 'attributes']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        try {
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $name = $request->file('file')->getClientOriginalName();
                $mimeType = $request->file('file')->getClientMimeType();
                $path = $request->file('file')->store('books');

                $file = $this->fileRepository->create(['name' => $name, 'mime_type' => $mimeType, 'path' => $path]);
                $input['file_id'] = $file->id;
            }

            $book = $this->bookRepository->create($input);
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $book->addMediaFromRequest('image')
                    ->toMediaCollection();
            }

            if ($request->has('attributes')) {
                foreach ($input['attributes'] as $attribute) {
                    $this->bookAttributeRepository->create(["book_id" => $book->id, "attribute_id" => $attribute]);
                }
            }
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
        return view('books.edit', compact(['book']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect(route('books.index'));
    }
}
