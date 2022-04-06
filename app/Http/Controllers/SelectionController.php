<?php

namespace App\Http\Controllers;

use App\DataTables\SelectionsDataTable;
use App\Models\Selection;
use App\Repositories\BookRepository;
use App\Repositories\BookSelectionRepository;
use App\Repositories\SelectionRepository;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;
use Flash;

class SelectionController extends Controller
{
    /** @var SelectionRepository */
    private $selectionRepository;
    /** @var BookRepository */
    private $bookRepository;
    /** @var BookSelectionRepository */
    private $bookSelectionRepository;

    public function __construct(
        SelectionRepository $selectionRepository,
        BookRepository $bookRepository,
        BookSelectionRepository $bookSelectionRepository)
    {
        $this->selectionRepository = $selectionRepository;
        $this->bookRepository = $bookRepository;
        $this->bookSelectionRepository = $bookSelectionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param SelectionsDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(SelectionsDataTable $dataTable)
    {
        return $dataTable->render('selections.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $books = $this->bookRepository->all()->pluck('name', 'id');
        $selectedBooks = [];

        return view('selections.create', compact(['books', 'selectedBooks']));
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
            $selection = $this->selectionRepository->create($input);
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $selection->addMediaFromRequest('image')
                    ->toMediaCollection();
            }
            if ($request->has('books')) {
                foreach ($input['books'] as $book) {
                    $this->bookSelectionRepository->create(["selection_id" => $selection->id, "book_id" => $book]);
                }
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.selection')]));

        return redirect(route('selections.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Selection  $selection
     * @return \Illuminate\Http\Response
     */
    public function show(Selection $selection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Selection  $selection
     * @return \Illuminate\Http\Response
     */
    public function edit(Selection $selection)
    {
        $books = $this->bookRepository->all()->pluck('name', 'id');
        $selectedBooks = $selection->bookSelections()->pluck('id');

        return view('selections.edit', compact(['selection', 'books', 'selectedBooks']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Selection  $selection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Selection $selection)
    {
        $input = $request->all();
        try {
            $selection = $this->selectionRepository->update($input, $selection->id);
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $selection->clearMediaCollection();
                $selection->addMediaFromRequest('image')
                    ->toMediaCollection();
            }
            if ($request->has('books')) {
                $this->bookSelectionRepository->findByField('selection_id', $selection->id)->each->delete();
                foreach ($input['books'] as $book) {
                    $this->bookSelectionRepository->create(["selection_id" => $selection->id, "book_id" => $book]);
                }
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.selection')]));

        return redirect(route('selections.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Selection  $selection
     * @return \Illuminate\Http\Response
     */
    public function destroy(Selection $selection)
    {
        $selection->delete();
        $selection->clearMediaCollection();

        return redirect(route('selections.index'));
    }
}
