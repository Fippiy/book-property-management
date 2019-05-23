@extends('layouts.layout')

@section('title', 'SomeBooksDelete')

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
        書籍削除結果
      </div>

      <div class="isbn-result">
        @foreach ($answers as $answer)
        <div class="isbn-result__box">
          <div class="isbn-result__box--number">{{$answer['number']}}</div>
          <div class="isbn-result__box--detail">
            <div class="isbn-result__box--head">
              <span class="isbn-result__box--isbn">{{$answer['title']}}</span>
              <span class="isbn-result__box--msg">{{$answer['msg']}}</span>
            </div>
            @if ($answer['process'] == 'completion')
            <div class="isbn-result__box--bottom">
            </div>
            @endif
          </div>
        </div>
        @endforeach

    </div>
  </div>
@endsection
