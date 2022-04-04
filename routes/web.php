<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Your all Cache is cleared";
});

Auth::routes();

Route::get('storage/app/public/{id}/{conversion}/{filename?}', 'App\Http\Controllers\UploadController@storage');
Route::get('storage/{id}/{conversion}/{filename?}', 'App\Http\Controllers\UploadController@storage');

Route::get('firebase/sw-js', 'App\Http\Controllers\AppSettingController@initFirebase');

Route::group(['middleware' => ['permission:permissions.index', 'auth'], 'prefix' => 'permissions'], function () {
    Route::get('role-has-permission', 'App\Http\Controllers\PermissionController@roleHasPermission');
    Route::get('refresh-permissions', 'App\Http\Controllers\PermissionController@refreshPermissions');

    Route::post('give-permission-to-role', 'App\Http\Controllers\PermissionController@givePermissionToRole');
    Route::post('revoke-permission-to-role', 'App\Http\Controllers\PermissionController@revokePermissionToRole');
});

Route::group(['middleware' => 'auth', 'prefix' => 'console'], function () {
    Route::get('/', 'App\Http\Controllers\DashboardController@index')->name('dashboard');

    Route::post('uploads/store', 'App\Http\Controllers\UploadController@store')->name('medias.create');
    Route::get('users/profile', 'App\Http\Controllers\UserController@profile')->name('users.profile');
    Route::post('users/remove-media', 'App\Http\Controllers\UserController@removeMedia');
    Route::resource('users', 'App\Http\Controllers\UserController');
    Route::get('dashboard', 'App\Http\Controllers\DashboardController@index')->name('dashboard');

    Route::group(['middleware' => ['permission:medias']], function () {
        Route::get('uploads/all/{collection?}', 'App\Http\Controllers\UploadController@all');
        Route::get('uploads/collectionsNames', 'App\Http\Controllers\UploadController@collectionsNames');
        Route::post('uploads/clear', 'App\Http\Controllers\UploadController@clear')->name('medias.delete');
        Route::get('medias', 'App\Http\Controllers\UploadController@index')->name('medias');
        Route::get('uploads/clear-all', 'App\Http\Controllers\UploadController@clearAll');
    });

    Route::group(['middleware' => ['permission:app-settings'], 'prefix' => 'settings'], function () {
        Route::resource('permissions', 'App\Http\Controllers\PermissionController');
        Route::resource('roles', 'App\Http\Controllers\RoleController');
        Route::resource('currencies', 'App\Http\Controllers\CurrencyController');
        Route::get('users/login-as-user/{id}', 'App\Http\Controllers\UserController@loginAsUser')->name('users.login-as-user');
        Route::patch('update', 'App\Http\Controllers\AppSettingController@update');
        Route::get('clear-cache', 'App\Http\Controllers\AppSettingController@clearCache');
        // disable special character and number in route params
        Route::get('/{type?}/{tab?}', 'App\Http\Controllers\AppSettingController@index')
            ->where('type', '[A-Za-z]*')->where('tab', '[A-Za-z]*')->name('app-settings');
    });

});
