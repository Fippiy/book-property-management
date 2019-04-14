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
    // return view('welcome');
  return view('index');
});

Route::get('/book/isbn', 'BookController@getIsbn')->middleware('auth');
Route::post('/book/isbn', 'BookController@postIsbn')->middleware('auth');
Route::get('/book/find', 'BookController@find')->middleware('auth');
Route::post('/book/find', 'BookController@search')->middleware('auth');
Route::resource('book', 'BookController')->middleware('auth');
Route::resource('property', 'PropertyController')->middleware('auth');
Route::post('/user/signup', 'UserController@postSignup');
Route::get('/user/signup', 'UserController@getSignup');
Route::post('/user/login', 'UserController@postLogin');
Route::get('/user/login', 'UserController@getLogin');
Route::get('/user/find', 'UserController@find')->middleware('auth');
Route::post('/user/find', 'UserController@search')->middleware('auth');
Route::resource('user', 'UserController')->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Route::get('/info', function () {
//     phpinfo();
// });
