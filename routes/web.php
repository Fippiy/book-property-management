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

// ログインルート
Auth::routes();

// ログインルートに巻き取り後削除
Route::post('/user/signup', 'UserController@postSignup');
Route::get('/user/signup', 'UserController@getSignup');
Route::post('/user/login', 'UserController@postLogin');
Route::get('/user/login', 'UserController@getLogin');

// 削除[ビューとコントローラ削除後]
Route::get('/', function () {
    // return view('welcome');
  return view('index');
});

// ログイン必須ページ
Route::group(['middleware' => ['auth']], function () {
  Route::get('/book/isbn', 'BookController@getIsbn');
  Route::post('/book/isbn', 'BookController@postIsbn');
  Route::get('/book/find', 'BookController@find');
  Route::post('/book/find', 'BookController@search');
  Route::resource('book', 'BookController');
  Route::get('/property/find', 'PropertyController@find');
  Route::post('/property/find', 'PropertyController@search');
  Route::resource('property', 'PropertyController');
  Route::resource('user', 'UserController');
});

// 削除[ビューとコントローラ削除後]
Route::get('/home', 'HomeController@index')->name('home');

// info情報表示用
// Route::get('/info', function () { phpinfo(); });
