<?php

namespace App\Http\Controllers;

use App\DataTables\TagDataTable;
use App\Models\Tag;
use App\Repositories\BookRepository;
use App\Repositories\TagRepository;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;
use Flash;

class TagController extends Controller
{
    private $tagRepository;
    private $bookRepository;

    public function __construct(
        TagRepository $tagRepository,
        BookRepository $bookRepository)
    {
        $this->tagRepository = $tagRepository;
        $this->bookRepository = $bookRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param TagDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(TagDataTable $dataTable)
    {
        return $dataTable->render('tags.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = ['genre' => 'genre', 'theme' => 'theme'];

        return view('tags.create', compact(['types']));
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
            $tag = $this->tagRepository->create($input);
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $tag->addMediaFromRequest('file')
                    ->toMediaCollection();
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.tag')]));

        return redirect(route('tags.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        return view('tags.show')->with('tag', $tag);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        $types = ['genre' => 'genre', 'theme' => 'theme'];

        return view('tags.edit')->with('tag', $tag)->with('types', $types);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $input = $request->all();
        try {
            $tag = $this->tagRepository->update($input, $tag->id);

            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $tag->clearMediaCollection();
                $tag->addMediaFromRequest('file')
                    ->toMediaCollection();
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.tag')]));

        return redirect(route('tags.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect(route('tags.index'));
    }
}
