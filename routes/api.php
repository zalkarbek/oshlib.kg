<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('books/{id}/pages/{page}', 'BookAPIController@byPage');

Route::middleware('throttle:1')->group(function() {
    Route::post('user/sendResetCode',  'ResetPasswordAPIController@sendResetCode');
});

Route::middleware('throttle:60')->group(function () {
    Route::post('user/code/check', 'ResetPasswordAPIController@checkResetCode');
    Route::post('user/password/reset', 'ResetPasswordAPIController@resetPasswordWithCode');
    Route::post('user/reset-password', 'ResetPasswordAPIController@resetPassword');

    Route::resource('books/selections', 'BookSelectionsAPIController');

    Route::get('books/reviews', 'BookAPIController@reviews');
    Route::get('books/{id}/preview', 'BookAPIController@bookPreview');
    Route::get('books/{id}/file', 'BookAPIController@bookFile');

    Route::get('/googleAuthRedirect', 'UserAPIController@redirectToProvider');
    Route::post('/googleAuth', 'UserAPIController@googleAuth');
    Route::post('/appleAuth', 'UserAPIController@appleAuth');

    Route::post('login', 'UserAPIController@login');
    Route::post('register', 'UserAPIController@register');
    Route::get('user/check', 'UserAPIController@userCheck');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('logout', 'UserAPIController@logout');
        Route::get('user', 'UserAPIController@user');
        Route::get('user/rented-books', 'RentBookAPIController@myRentedBooks');
        Route::post('user', 'UserAPIController@update');
        Route::patch('user/password', 'UserAPIController@changePassword');
        Route::resource('user/bookShelves', 'BookShelfAPIController');
        Route::delete('user/bookShelves/{id}/books', 'BookShelfAPIController@deleteBooksFromShelf');
        Route::delete('user/bookShelves/{id}/books/{bookId}', 'BookShelfAPIController@deleteBookFromShelf');

        Route::get('books/my/favorites', 'BookAPIController@favorites');
        Route::delete('books/{id}/mybooks', 'BookAPIController@deleteFromMyBooks');
        Route::group(['prefix' => 'books/{id}'], function () {
            Route::delete('readStatus', 'BookAPIController@deleteReadStatus');
            Route::post('wantToRead', 'BookAPIController@wantToRead');
            Route::post('reading', 'BookAPIController@reading');
            Route::post('read', 'BookAPIController@read');
            Route::post('favorites', 'BookAPIController@addToFavorites');
            Route::delete('favorites', 'BookAPIController@removeFromFavorites');
            Route::delete('rate', 'BookAPIController@deleteRate');
            Route::post('rate', 'BookAPIController@rate');
            Route::get('myreview', 'BookAPIController@myReview');
        });

        Route::post('rent-book', 'RentBookAPIController@createRent');
    });

    Route::resource('books', 'BookAPIController')->except([
        'store', 'update'
    ]);
    Route::get('categories/tree', 'CategoryAPIController@tree');
    Route::resource('categories', 'CategoryAPIController')->except([
        'store', 'update'
    ]);
    Route::get('categories/{id}/books', 'CategoryAPIController@books');

    Route::resource('tags', 'TagAPIController')->except([
        'store', 'update'
    ]);
    Route::get('genres', 'TagAPIController@genres');
    Route::get('themes', 'TagAPIController@themes');
    Route::get('tags/{id}/books', 'TagAPIController@books');

    Route::resource('authors', 'AuthorAPIController')->except([
        'store', 'update'
    ]);
    Route::get('authors/{id}/books', 'AuthorAPIController@books');

    Route::resource('publishers', 'PublisherAPIController')->except([
        'store', 'update'
    ]);
    Route::get('publishers/{id}/books', 'PublisherAPIController@books');

    Route::resource('articles/categories', 'ArticleCategoryAPIController')->except(['store', 'update']);
    Route::resource('articles', 'ArticleAPIController')->except(['store', 'update']);
});
