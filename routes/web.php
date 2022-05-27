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

Route::get('authors-fix', 'App\Http\Controllers\AuthorController@fix');
Route::get('authors-test', 'App\Http\Controllers\BookController@authorTest');

Route::get('terms-of-reader', function () {
    return view('settings.privacy_policy.terms_of_reader');
});

Route::get('/privacy-policy', function () {
    return view('settings.privacy_policy.index');
});

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Your all Cache is cleared";
});

Route::get('/googlecallback', 'App\Http\Controllers\UserController@handleProviderCallback');

Auth::routes();

Route::get('storage/cover/{id}', 'App\Http\Controllers\UploadController@bookCover');
Route::get('storage/app/public/{id}/{conversion}/{filename?}', 'App\Http\Controllers\UploadController@storage');
Route::get('storage/{id}/{conversion}/{filename?}', 'App\Http\Controllers\UploadController@storage');

Route::get('firebase/sw-js', 'App\Http\Controllers\AppSettingController@initFirebase');

Route::group(['middleware' => ['permission:permissions.index', 'auth'], 'prefix' => 'permissions'], function () {
    Route::get('role-has-permission', 'App\Http\Controllers\PermissionController@roleHasPermission');
    Route::get('refresh-permissions', 'App\Http\Controllers\PermissionController@refreshPermissions');

    Route::post('give-permission-to-role', 'App\Http\Controllers\PermissionController@givePermissionToRole');
    Route::post('revoke-permission-to-role', 'App\Http\Controllers\PermissionController@revokePermissionToRole');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'App\Http\Controllers\DashboardController@index');

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

    Route::post('categories/remove-media', 'App\Http\Controllers\CategoryController@removeMedia');
    Route::resource('categories', 'App\Http\Controllers\CategoryController');
    Route::resource('tags', 'App\Http\Controllers\TagController');
    Route::resource('authors', 'App\Http\Controllers\AuthorController');
    Route::resource('publishers', 'App\Http\Controllers\PublisherController');
    Route::resource('attributes', 'App\Http\Controllers\AttributeController');
    Route::resource('books', 'App\Http\Controllers\BookController');
    Route::resource('articles/categories', 'App\Http\Controllers\ArticleCategoryController')->names('articles.categories');
    Route::resource('articles', 'App\Http\Controllers\ArticleController');
    Route::resource('reviews', 'App\Http\Controllers\ReviewController');
    Route::resource('selections', 'App\Http\Controllers\SelectionController');
    Route::resource('rented-books', 'App\Http\Controllers\RentedBooksController');
    Route::resource('readers', 'App\Http\Controllers\ReaderController');
    Route::get('select-books', 'App\Http\Controllers\BookController@selectBooksTable');

});
