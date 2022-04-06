<?php

namespace App\Http\Controllers;

use App\DataTables\ReviewDataTable;
use App\Models\Review;
use App\Repositories\BookRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;
use Flash;

class ReviewController extends Controller
{
    /** @var ReviewRepository */
    private $reviewRepository;
    /** @var UserRepository */
    private $userRepository;
    /** @var BookRepository */
    private $bookRepository;

    public function __construct(
        ReviewRepository $reviewRepository,
        BookRepository $bookRepository,
        UserRepository $userRepository)
    {
        $this->reviewRepository = $reviewRepository;
        $this->bookRepository = $bookRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ReviewDataTable $dataTable
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ReviewDataTable $dataTable)
    {
        return $dataTable->render('reviews.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $books = $this->bookRepository->all()->pluck('name', 'id');
        $users = $this->userRepository->all()->pluck('name', 'id');

        return view('reviews.create', compact(['books', 'users']));
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
            $review = $this->reviewRepository->create($input);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.review')]));

        return redirect(route('reviews.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        return view('reviews.show', compact(['review']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        $books = $this->bookRepository->all()->pluck('name', 'id');
        $users = $this->userRepository->all()->pluck('name', 'id');

        return view('reviews.edit', compact(['review', 'books', 'users']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        $input = $request->all();
        try {
            $review = $this->reviewRepository->update($input, $review->id);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.review')]));

        return redirect(route('reviews.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return redirect(route('reviews.index'));
    }
}
