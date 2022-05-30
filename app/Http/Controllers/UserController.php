<?php
/**
 * File name: UserController.php
 * Last modified: 2020.06.11 at 16:10:52
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 */

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserBookShelfRepository;
use App\Repositories\UserRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Laravel\Socialite\Facades\Socialite;
use Prettus\Validator\Exceptions\ValidatorException;

class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;
    /** @var RoleRepository */
    private $roleRepository;
    /** @var UploadRepository */
    private $uploadRepository;



    public function __construct(
        UserRepository $userRepo,
        RoleRepository $roleRepo)
    {
        $this->userRepository = $userRepo;
        $this->roleRepository = $roleRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param UserDataTable $userDataTable
     * @return Response
     */
    public function index(UserDataTable $userDataTable)
    {
        return $userDataTable->render('settings.users.index');
    }

    /**
     * Display a user profile.
     *
     * @param
     * @return Response
     */
    public function profile()
    {
        $user = $this->userRepository->findWithoutFail(auth()->id());
        unset($user->password);
        if (auth()->user()->hasRole('sadmin')) {
            $role = $this->roleRepository->all()->pluck('name', 'name');
        } else {
            $role = $this->roleRepository->all()->where('name', '!=', 'sadmin')->pluck('name', 'name');
        }
        $rolesSelected = $user->getRoleNames()->toArray();

        return view('settings.users.profile', compact(['user', 'role', 'rolesSelected']));
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        if (auth()->user()->hasRole('sadmin')) {
            $role = $this->roleRepository->all()->pluck('name', 'name');
        } else {
            $role = $this->roleRepository->all()->where('name', '!=', 'sadmin')->pluck('name', 'name');
        }

        $rolesSelected = [];

        return view('settings.users.create')
            ->with("role", $role)
            ->with("rolesSelected", $rolesSelected);
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('users.index'));
        }

        $input = $request->all();

        $input['roles'] = isset($input['roles']) ? $input['roles'] : [];
        $input['password'] = Hash::make($input['password']);
        $input['api_token'] = str_random(60);

        try {
            $user = $this->userRepository->create($input);
            $user->syncRoles($input['roles']);

            if (isset($input['avatar']) && $input['avatar']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['avatar']);
                $mediaItem = $cacheUpload->getMedia('avatar')->first();
                $mediaItem->copy($user, 'avatar');
            }
            event(new UserRoleChangedEvent($user));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success('saved successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('settings.users.profile')->with('user', $user);
    }

    public function loginAsUser(Request $request, $id)
    {
        $user = $this->userRepository->findWithoutFail($id);
        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('users.index'));
        }
        auth()->login($user, true);
        if (auth()->id() !== $user->id) {
            Flash::error('User not found');
        }
        return redirect(route('users.profile'));
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        if (!((auth()->user()->hasRole('sadmin|admin'))
            || $id != auth()->id())) {
            Flash::error('Нет разрешений');
            return redirect(route('users.index'));
        }
        $user = $this->userRepository->findWithoutFail($id);
        unset($user->password);
        $html = false;
        $role = $this->roleRepository->pluck('name', 'name');
        $rolesSelected = $user->getRoleNames()->toArray();

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }
        return view('settings.users.edit')
            ->with('user', $user)->with("role", $role)
            ->with("rolesSelected", $rolesSelected);
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        if (!(auth()->user()->hasRole('dev'))) {
            Flash::error('Нет разрешений');
            return redirect(route('users.profile'));
        }

        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('users.profile'));
        }

        $input = $request->all();
        if (!auth()->user()->can('permissions.index')) {
            unset($input['roles']);
        } else {
            $input['roles'] = isset($input['roles']) ? $input['roles'] : [];
        }
        if (empty($input['password'])) {
            unset($input['password']);
        } else {
            $input['password'] = Hash::make($input['password']);
        }
        try {
            $user = $this->userRepository->update($input, $id);
            if (empty($user)) {
                Flash::error('User not found');
                return redirect(route('users.profile'));
            }
            if (isset($input['avatar']) && $input['avatar']) {
                $cacheUpload = $this->uploadRepository->getByUuid($input['avatar']);
                $mediaItem = $cacheUpload->getMedia('avatar')->first();
                $mediaItem->copy($user, 'avatar');
            }
            if (auth()->user()->can('permissions.index')) {
                $user->syncRoles($input['roles']);
            }

            // event(new UserRoleChangedEvent($user));
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }


        Flash::success('User updated successfully.');

        return redirect()->back();

    }

    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
            return redirect(route('users.index'));
        }
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Remove Media of User
     * @param Request $request
     */
    public function removeMedia(Request $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning('This is only demo app you can\'t change this section ');
        } else {
            if (auth()->user()->can('medias.delete')) {
                $input = $request->all();
                $user = $this->userRepository->findWithoutFail($input['id']);
                try {
                    if ($user->hasMedia($input['collection'])) {
                        $user->getFirstMedia($input['collection'])->delete();
                    }
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        }
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 505);
        }

        // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser) {
            auth()->login($existingUser, true);
        } else {
            // create a new user
            $newUser                  = new User;
            $newUser->name            = $user->name;
            $newUser->email           = $user->email;
            $newUser->password = Hash::make(str_random(20));
            $newUser->comment = '';
            $newUser->save();

            if ($user->getAvatar()) {
                $newUser->addMediaFromUrl($user->avatar)->toMediaCollection();
            }

            $existingUser = $newUser;

            auth()->login($newUser, true);
        }

        $data = $existingUser->toArray();
        $data['token'] = $existingUser->createToken(str_random(20))->plainTextToken;

        return $this->sendResponse($data, 'Success');
    }

}
