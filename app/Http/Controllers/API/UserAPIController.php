<?php
/**
 * File name: UserAPIController.php
 * Last modified: 2020.06.11 at 12:09:19
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 */

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\BookShelf;
use App\Models\User;
use App\Models\UserBookShelf;
use App\Repositories\AdvertisementRepository;
use App\Repositories\BookRepository;
use App\Repositories\BookShelfRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserBookShelfRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;
use Prettus\Validator\Exceptions\ValidatorException;
use Auth;

class UserAPIController extends AppBaseController
{
    private $userRepository;
    private $roleRepository;
    private $bookRepository;
    /** @var UserBookShelfRepository */
    private $userBookShelfRepository;
    /** @var BookShelfRepository */
    private $bookShelfRepository;

    public function __construct(
        UserRepository $userRepository,
        RoleRepository $roleRepository,
        BookRepository $bookRepository,
        UserBookShelfRepository $userBookShelfRepository,
        BookShelfRepository $bookShelfRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->bookRepository = $bookRepository;
        $this->userBookShelfRepository = $userBookShelfRepository;
        $this->bookShelfRepository = $bookShelfRepository;
    }

    function login(LoginRequest $request)
    {
        try {
            if (auth()->attempt([findUsername() => $request->input('login'), 'password' => $request->input('password')])) {
                // Authentication passed...
                $user = auth()->user();
                $user->fcm_token = $request->input('fcm_token', '');
                $token = $user->createToken(str_random(20));
                $user->save();
                $user->token = $token->plainTextToken;
                return $this->sendResponse($user, 'User retrieved successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 405);
        }

        return $this->sendError([], 401);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param RegisterRequest $request
     * @return
     */
    function register(RegisterRequest $request)
    {
        try {
            $user = new User;
            $user->name = $request->input('name');
            $user->login = $request->input('login');
            $user->email = $request->input('email');
            $user->fcm_token = $request->input('fcm_token', '');
            $user->password = Hash::make($request->input('password'));

            if ($request->has('google_account')) {
                $user->google_account = true;
                $user->email_verified_at = Carbon::now();
            }

            $user->save();

            $defaultRoles = $this->roleRepository->find(3);
            $defaultRoles = $defaultRoles->pluck('name')->toArray();
            $user->assignRole($defaultRoles);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 405);
        }

        $data = $user->toArray();
        $data['token'] = $user->createToken(str_random(20))->plainTextToken;
        return $this->sendResponse($data, 'User retrieved successfully');
    }

    function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            auth()->logout();
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }

        return $this->sendResponse(auth()->user()->name, 'User logout successfully');

    }

    function user(Request $request)
    {
        return $this->sendResponse($request->user(), 'User retrieved successfully');
    }

    function settings(Request $request)
    {
        $settings = setting()->all();
        $settings = array_intersect_key($settings,
            [
                'app_name' => '',
                'currency_right' => '',
                'main_color' => '',
                'main_dark_color' => '',
                'second_color' => '',
                'second_dark_color' => '',
                'accent_color' => '',
                'accent_dark_color' => '',
                'scaffold_dark_color' => '',
                'scaffold_color' => '',
                'app_version' => '',
                'enable_version' => '',
            ]
        );

        if (!$settings) {
            return $this->sendError('Settings not found', 401);
        }

        return $this->sendResponse($settings, 'Settings retrieved successfully');
    }

    function sendResetPassword(Request $request)
    {
        Password::sendResetLink();
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param Request $request
     *
     */
    public function update($id, Request $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], 'User not found');
        }
        $input = $request->except(['password']);
        try {
            if ($request->has('device_token')) {
                $user = $this->userRepository->update($request->only('device_token'), $id);
            } else {
                $user = $this->userRepository->update($input, $id);
                if ($request->hasFile('avatar')) {
                    $user->clearMediaCollection();
                    $user->addMediaFromRequest('avatar')
                        ->toMediaCollection();
                }
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage(), 401);
        }

        return $this->sendResponse($user, __('lang.updated_successfully', ['operator' => __('lang.user')]));
    }

    public function userUpdate(Request $request)
    {
        $id = auth()->user()->id;
        $input = $request->except(['password']);

        try {
            if ($request->has('fcm_token')) {
                $user = $this->userRepository->update($request->only('fcm_token'), $id);
            } else {
                $user = $this->userRepository->update($input, $id);
                if ($request->hasFile('avatar')) {
                    $user->clearMediaCollection();
                    $user->addMediaFromRequest('avatar')
                        ->toMediaCollection();
                }
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage(), 401);
        }

        return $this->sendResponse($user, __('lang.updated_successfully', ['operator' => __('lang.user')]));
    }

    public function userCheck(Request $request)
    {
        if ($request->has('login')) {
            $field = findUsername();
            $user = $this->userRepository->findByField($field, $request->input('login'))->first();

            if ($user) {
                return $this->sendResponse($field, 'User found');
            }
        }

        return $this->sendError('User not found', 404);
    }

    public function changePassword(Request $request)
    {
        if (auth()->check()) {
            if (!$request->has('new_password')) {
                $this->sendError('New password empty', 401);
            }

            $user = $this->userRepository->find(auth()->id());

            if ($user) {
                $user->password = Hash::make($request->input('new_password'));
                // $user->api_token = str_random(60);
                $user->save();
                return $this->sendResponse($user->makeHidden(['api_token', 'device_token', 'balance', 'uuid']), 'User retrieved successfully');
            }
        } else if ($request->has('phone') && $request->has('uuid')) {
            if (!$request->has('new_password')) {
                $this->sendError('New password empty', 401);
            }

            $user = $this->userRepository->findByField('phone', $request->input('phone'))->first();

            if ($user && $user->uuid === $request->input('uuid')) {
                $user->password = Hash::make($request->input('new_password'));
                // $user->api_token = str_random(60);
                $user->save();
                return $this->sendResponse($user->makeHidden(['api_token', 'device_token', 'balance', 'uuid']), 'User retrieved successfully');
            }
        }

        return $this->sendError('User not found', 404);
    }

    public function registerFcmToken(Request $request)
    {
        if ($request->has('fcm_token')) {
            $user = $this->userRepository->update(['fcm_token' => $request->input('device_token')], auth()->id());

            return $this->sendResponse($user->makeHidden(['fcm_token']), 'Saved successfully');
        }

        return $this->sendError('Device token not found', 405);
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param Request $request
     * @return
     */
    function googleAuth(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users',
            'name' => 'required|string',
            'uid' => 'required|string',
        ]);

        $input = $request->all();
        // check if they're an existing user
        $user = User::where('email', $input['email'])->where('uid', $input['uid'])->first();
        if($user) {
            auth()->login($user, true);
        } else {
            // create a new user
            $user                  = new User;
            $user->name            = $input['name'];
            $user->email           = $input['email'];
            $user->fcm_token = $request->input('fcm_token', '');
            $user->uid = $input['uid'];
            $user->password = Hash::make(str_random(20));
            $user->google_account = true;
            $user->email_verified_at = Carbon::now();
            $user->comment = '';
            $user->save();

            $defaultRoles = $this->roleRepository->find(3);
            $defaultRoles = $defaultRoles->pluck('name')->toArray();
            $user->assignRole($defaultRoles);

            auth()->login($user, true);
        }

        $data = $user->toArray();
        $data['token'] = $user->createToken(str_random(20))->plainTextToken;

        return $this->sendResponse($data, 'Success');
    }

    public function bookShelves(Request $request)
    {
        return $request->user()->bookShelves();
    }
}
