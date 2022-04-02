<?php

namespace App\Http\Controllers;

use App\Repositories\AdvertisementRepository;
use App\Repositories\BookRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;

class DashboardController extends AppBaseController
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var BookRepository
     */
    private $bookRepository;

    public function __construct(
        UserRepository $userRepo,
        BookRepository $bookRepo)
    {
        $this->userRepository = $userRepo;
        $this->bookRepository = $bookRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $advertisements = $adsCount = $this->bookRepository->limit(5);
        $adsCount = $advertisements->count();
        $adsThisMonthCount = $advertisements->where('created_at', '>=', Carbon::now()->month()->toDateTimeString())->count();
        $adsThisYearCount = $advertisements->where('created_at', '>=', Carbon::now()->year()->toDateTimeString())->count();
        $usersCount = $this->userRepository->all()->count();

        return view('dashboard.index')
            ->with("ajaxEarningUrl", '')
            ->with("adsCount", $adsCount)
            ->with("adsThisMonthCount", $adsThisMonthCount)
            ->with("adsThisYearCount", $adsThisYearCount)
            ->with("usersCount", $usersCount)
            ->with("advertisements", $advertisements)
            ->with("earning", 0);
    }
}
