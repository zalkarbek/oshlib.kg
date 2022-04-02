<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use Prettus\Validator\Exceptions\ValidatorException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    private $userRepository;
    private $uploadRepository;
    private $roleRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        UserRepository $userRepository,
        UploadRepository $uploadRepository,
        RoleRepository $roleRepository)
    {
        $this->middleware('guest')->except('logout');
        $this->userRepository = $userRepository;
        $this->uploadRepository = $uploadRepository;
        $this->roleRepository = $roleRepository;
    }

    public function username()
    {
        return 'login';
    }

}
