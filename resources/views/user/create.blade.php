@extends('layouts.layout')

@section('title', 'CreateForm')

@section('stylesheet')
  <link href="/css/menulist.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('user.create') }}
  </div>
@endsection

@section('pagemenu')
  @include('components.menu_mypage')
@endsection

@section('content')
  <div class="index-content">
    <div class="books-list">
      <div class="books-list__title mypage-color">
        所有書籍登録
      </div>
      <div class="book-new">
        <form action="/user/store" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="form-contents">
            <div class="form-one-size">
              <div class="form-input">
                <select class="form-input__select" name="have-book">
                  <option value="test1">テスト1</option>
                  <option value="test2">テスト2</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-foot">
            <input class="send" type="submit" value="登録">
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
