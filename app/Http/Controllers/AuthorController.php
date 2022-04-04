<?php

namespace App\Http\Controllers;

use App\DataTables\AuthorDataTable;
use App\Models\Author;
use App\Repositories\AuthorRepository;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;
use Flash;

class AuthorController extends Controller
{
    /** @var AuthorRepository */
    private $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    /**
     * Display a listing of the resource.
     * @param AuthorDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(AuthorDataTable $dataTable)
    {
        return $dataTable->render('authors.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('authors.create');
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
            $author = $this->authorRepository->create($input);
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $author->addMediaFromRequest('file')
                    ->toMediaCollection();
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.author')]));

        return redirect(route('authors.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        return view('categories.show')->with('author', $author);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author)
    {
        return view('authors.edit')->with('author', $author);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {
        $input = $request->all();
        try {
            $author = $this->authorRepository->update($input, $author->id);

            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $author->clearMediaCollection();
                $author->addMediaFromRequest('file')
                    ->toMediaCollection();
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.author')]));

        return redirect(route('authors.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        $author->delete();

        return redirect(route('authors.index'));
    }
}
