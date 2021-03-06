@extends('layouts.layout')

@section('title', 'ISBN')

@section('stylesheet')
<link href="/css/menulist.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('book.isbn_some_input') }}
  </div>
@endsection

@section('pagemenu')
  @include('components.menu_book')
@endsection

@section('content')
  <div class="index-content">
    <div class="books-list">
      <div class="books-list__title bookpage-color">
        ISBNコード登録
      </div>
      <div class="books-list__msg">
        <p class="auth-contents__message--message">{{ $msg }}</p>
        @foreach ($errors->all() as $error)
        <p class="auth-contents__message--error">{{ $error }}</p>
        @endforeach
      </div>
      <div class="book-new">
        <form action="/book/isbn_some" method="post">
          {{ csrf_field() }}
          <div class="form-contents">
            <div class="form-input form-one-size">
              <div class="form-label">ISBNコード[20件まで]（カンマ区切り、改行で連続投入可能）</div>
              <div><textarea class="form-input__detail" type="numbers" name="isbns"></textarea></div>
            </div>
          </div>
          <div class="form-foot">
            <input class="send isbn" type="submit" value="登録">
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
