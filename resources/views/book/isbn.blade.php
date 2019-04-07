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
      <div class="book-new">
        <form action="/book/isbn" method="post">
          {{ csrf_field() }}
          <div class="form-contents">
            <div class="form-left">
            </div>
            <div class="form-right">
              <div class="form-input">
                <div class="form-label">ISBNコード</div>
                <div><input class="form-input__title" type="number" name="isbn" value="{{old('isbn')}}"></div>
                @if (isset($msg))
                  <div>{{$msg}}</div>
                @endif
                @if (isset($data))
                  <div>{{$data->title}}</div>
                @endif
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
