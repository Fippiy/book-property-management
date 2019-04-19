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

// トップページ
Route::get('/', function () {
  return view('index');
});

// ログインルート
Auth::routes(['verify' => true]);

// ログイン必須ページ
Route::group(['middleware' => ['auth']], function () {
  Route::get('/book/isbn', 'BookController@getIsbn');
  Route::post('/book/isbn', 'BookController@postIsbn');
  Route::get('/book/find', 'BookController@find')->name('book.find');
  Route::post('/book/find', 'BookController@search')->name('book.find');
  Route::resource('book', 'BookController');
  Route::get('/property/find', 'PropertyController@find')->name('property.find');
  Route::post('/property/find', 'PropertyController@search')->name('property.find');
  Route::resource('property', 'PropertyController');
  Route::resource('user', 'UserController');
});

// info情報表示用
// Route::get('/info', function () { phpinfo(); });
