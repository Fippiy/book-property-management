@extends('layouts.layout')

@section('title', 'ISBN')

@section('stylesheet')
<link href="/css/menulist.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('book.isbn_some') }}
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
              <div class="form-label">ISBNコード</div>
              @for ($i = 0; $i < 10; $i++)
                <div><input class="form-input__input" type="number" name="isbn{{$i}}"></div>
              @endfor
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
