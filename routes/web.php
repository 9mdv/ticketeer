<?php

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
    return 'Laravel';
});

Route::get('/concerts/{id}', 'ConcertController@show')->name('concerts.show');
Route::post('/concerts/{id}/orders', 'ConcertOrderController@store');
Route::get('/orders/{confirmationNumber}', 'OrderController@show');

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');

Route::middleware('auth')->prefix('backstage')->namespace('Backstage')->group(function () {
    Route::get('/concerts', 'ConcertController@index');
    Route::get('/concerts/new', 'ConcertController@create');
    Route::post('/concerts', 'ConcertController@store');
});
