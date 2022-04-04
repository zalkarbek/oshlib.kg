<?php

namespace App\Http\Controllers;

use App\DataTables\PublisherDataTable;
use App\Models\Publisher;
use App\Repositories\PublisherRepository;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;
use Flash;

class PublisherController extends Controller
{
    /** @var PublisherRepository */
    private $publisherRepository;

    public function __construct(PublisherRepository $publisherRepository)
    {
        $this->publisherRepository = $publisherRepository;
    }

    /**
     * Display a listing of the resource.
     * @param PublisherDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(PublisherDataTable $dataTable)
    {
        return $dataTable->render('publishers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('publishers.create');
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
            $publisher = $this->publisherRepository->create($input);
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $publisher->addMediaFromRequest('file')
                    ->toMediaCollection();
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.publisher')]));

        return redirect(route('publishers.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function show(Publisher $publisher)
    {
        return view('publishers.show')->with('publisher', $publisher);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function edit(Publisher $publisher)
    {
        return view('publishers.edit')->with('publisher', $publisher);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Publisher $publisher)
    {
        $input = $request->all();
        try {
            $publisher = $this->publisherRepository->update($input, $publisher->id);

            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $publisher->clearMediaCollection();
                $publisher->addMediaFromRequest('file')
                    ->toMediaCollection();
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.publisher')]));

        return redirect(route('publishers.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publisher $publisher)
    {
        $publisher->delete();

        return redirect(route('publishers.index'));
    }
}
