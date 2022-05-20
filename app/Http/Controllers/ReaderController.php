<?php

namespace App\Http\Controllers;

use App\DataTables\ReaderDataTable;
use App\Models\Reader;
use App\Models\User;
use App\Repositories\ReaderRepository;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;
use Flash;

class ReaderController extends Controller
{
    /** @var ReaderRepository */
    private $readerRepository;

    public function __construct(ReaderRepository $readerRepository)
    {
        $this->readerRepository = $readerRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ReaderDataTable
     * @return \Illuminate\Http\Response
     */
    public function index(ReaderDataTable $dataTable)
    {
        return $dataTable->render('readers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all()->pluck('name', 'id');
        $users->prepend('Нет', null);

        return view('readers.create', compact(['users']));
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
            $reader = $this->readerRepository->create($input);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.reader')]));

        return redirect(route('readers.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Reader  $reader
     * @return \Illuminate\Http\Response
     */
    public function show(Reader $reader)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Reader  $reader
     * @return \Illuminate\Http\Response
     */
    public function edit(Reader $reader)
    {
        $users = User::all()->pluck('name', 'id');
        $users->prepend('Нет', null);

        return view('readers.edit', compact(['reader', 'users']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reader  $reader
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reader $reader)
    {
        $input = $request->all();
        try {
            $reader = $this->readerRepository->update($input, $reader->id);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.reader')]));

        return redirect(route('readers.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reader  $reader
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reader $reader)
    {
        $reader->delete();

        return redirect(route('readers.index'));
    }
}
