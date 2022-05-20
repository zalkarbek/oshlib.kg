<?php

namespace App\Http\Controllers;

use App\DataTables\RentedBookDataTable;
use App\Models\Reader;
use App\Models\RentedBooks;
use App\Repositories\ReaderRepository;
use App\Repositories\RentedBooksRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;
use Flash;

class RentedBooksController extends Controller
{
    /** @var ReaderRepository */
    private $readerRepository;
    /** @var RentedBooksRepository */
    private $rentedBooksRepository;

    public function __construct(
        ReaderRepository $readerRepository,
        RentedBooksRepository $rentedBooksRepository)
    {
        $this->readerRepository = $readerRepository;
        $this->rentedBooksRepository = $rentedBooksRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param RentedBookDataTable
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RentedBookDataTable $dataTable)
    {
        return $dataTable->render('rented-books.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = Reader::join('users', 'users.id', '=', 'readers.user_id')->pluck('users.name', 'readers.id');

        return view('rented-books.create', compact(['users']));
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
            $rentedBook = $this->rentedBooksRepository->create($input);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.rented-book')]));

        return redirect(route('rented-books.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RentedBooks  $rentedBooks
     * @return \Illuminate\Http\Response
     */
    public function show(RentedBooks $rentedBooks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  RentedBooks  $rentedBook
     * @return \Illuminate\Http\Response
     */
    public function edit(RentedBooks $rentedBook)
    {
        $users = Reader::join('users', 'users.id', '=', 'readers.user_id')->pluck('users.name', 'readers.id');
        $issueDate = Carbon::parse($rentedBook->issue_date);
        $returnDate = Carbon::parse($rentedBook->return_date);

        return view('rented-books.edit', compact(['rentedBook', 'users', 'issueDate', 'returnDate']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  RentedBooks  $rentedBook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RentedBooks $rentedBook)
    {
        $input = $request->all();
        $input['bail_received'] = $input['bail_received'] ?? '0';

        try {
            $rentedBook = $this->rentedBooksRepository->update($input, $rentedBook->id);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.rented-book')]));

        return redirect(route('rented-books.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->rentedBooksRepository->delete($id);

        return redirect(route('rented-books.index'));
    }
}
