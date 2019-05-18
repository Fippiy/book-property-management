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
        ISBNコード登録結果
      </div>

      <div class="isbn-result">
        @foreach ($answers as $answer)
        <div class="isbn-result__box">
          <div class="isbn-result__box--number">{{$answer['number']}}</div>
          <div class="isbn-result__box--detail">
            <div class="isbn-result__box--head">
              <span class="isbn-result__box--isbn">{{$answer['isbn']}}</span>
              <span class="isbn-result__box--msg">{{$answer['msg']}}</span>
            </div>
            @if ($answer['process'] == 'completion')
            <div class="isbn-result__box--bottom">
              <div class="isbn-result__box--image">
              @if (isset($answer['result']['cover']) == null)
                <img src="../image/no-entry.jpg">
              @else
                <img src="{{$answer['result']['cover']}}">
              @endif
              </div>
              <div class="isbn-result__box--title">{{$answer['result']['title']}}</div>
            </div>
            @endif
          </div>
        </div>
        @endforeach

    </div>
  </div>
@endsection
