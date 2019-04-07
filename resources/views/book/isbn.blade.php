@extends('layouts.layout')

@section('title', 'ISBN')

@section('stylesheet')
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('book.isbn') }}
  </div>
@endsection

@section('content')
  <!-- サブメニュー(コンポーネント) -->
  @component('components.menu_book')
  @endcomponent
  <div class="index-content">
    <div class="books-list">
      <div class="books-list__title">
        ISBNコード登録
      </div>
      @if (isset($msg))
        <div class="books-list__msg">
            <span>{{$msg}}</span>
        </div>
      @endif
      <div class="book-new">
        <form action="/book/isbn" method="post">
          {{ csrf_field() }}
          <div class="form-contents">
            <div class="form-input form-one-size">
              <div class="form-label">ISBNコード</div>
              <div><input class="form-input__detail" type="number" name="isbn" value="{{old('isbn')}}"></div>
            </div>
          </div>
          <div class="form-foot">
            <input class="send isbn" type="submit" value="登録">
          </div>
        </form>
      </div>
      <div class="book-new">
      </div>
      @if (isset($book))
        @include('components.book_detail')
      @endif
    </div>
  </div>
@endsection
