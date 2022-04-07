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

Route::post('login', 'UserAPIController@login');
Route::post('register', 'UserAPIController@register');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', 'UserAPIController@logout');
    Route::get('user', 'UserAPIController@user');
});

Route::resource('books', 'BookAPIController')->except([
    'store', 'update'
]);
Route::get('books/reviews', 'BookAPIController@reviews');
Route::get('books/{id}/page/{page}', 'BookAPIController@byPage');

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

Route::resource('authors', 'AuthorAPIController')->except([
    'store', 'update'
]);
Route::get('authors/{id}/books', 'AuthorAPIController@books');

Route::resource('publishers', 'PublisherAPIController')->except([
    'store', 'update'
]);
Route::get('publishers/{id}/books', 'PublisherAPIController@books');

Route::resource('articles', 'PublisherAPIController')->except([
    'store', 'update'
]);
