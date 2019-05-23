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
Route::group(['middleware' => ['verified']], function () {
  Route::get('/book/isbn', 'BookController@getIsbn');
  Route::post('/book/isbn', 'BookController@postIsbn');
  Route::get('/book/isbn_some', 'BookController@getIsbnSome');
  Route::post('/book/isbn_some', 'BookController@postIsbnSome');
  Route::get('/book/isbn_some_input', 'BookController@getIsbnSomeInput');
  Route::get('/book/find', 'BookController@find')->name('book.find');
  Route::post('/book/find', 'BookController@search')->name('book.find');
  Route::post('/book/somedelete', 'BookController@somedelete')->name('book.some_delete');
  Route::resource('book', 'BookController');
  Route::get('/property/find', 'PropertyController@find')->name('property.find');
  Route::post('/property/find', 'PropertyController@search')->name('property.find');
  Route::post('/property/somedelete', 'PropertyController@somedelete')->name('property.some_delete');
  Route::resource('property', 'PropertyController');
  Route::get('/user/email', 'UserController@userEmailEdit')->name('email.edit');
  Route::post('/user/email', 'UserController@userEmailChange')->name('email.change');
  Route::get('/user/userEmailUpdate/', 'UserController@userEmailUpdate');
  Route::get('/user/delete', 'UserController@delete');
  Route::get('/user/{page}', 'UserController@useredit')->name('user.edit');
  Route::post('/user/{page}', 'UserController@update')->name('user.update');
  Route::resource('user', 'UserController',['except' => ['show', 'edit']]);
});

// info情報表示用
// Route::get('/info', function () { phpinfo(); });
