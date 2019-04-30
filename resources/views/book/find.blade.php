@extends('layouts.layout')

@section('title', 'FindPage')

@section('stylesheet')
<link href="/css/menulist.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('book.find') }}
  </div>
@endsection

@section('pagemenu')
  @include('components.menu_book')
@endsection

@section('content')
  <div class="index-content">
    <div class="books-list">
      <div class="books-list__title bookpage-color">
        検索
      </div>
      <div class="books-list__msg">
        <p class="auth-contents__message--message">{{ $msg }}</p>
        @foreach ($errors->all() as $error)
        <p class="auth-contents__message--error">{{ $error }}</p>
        @endforeach
      </div>
      <form class="book-find" action="{{ route('book.find') }}" method="post">
        {{ csrf_field() }}
        <div class="book-find__input">
          <input type="text" class="book-find__input--text" name="find" value="{{$input}}">
          <input type="submit" class="book-find__input--submit" value="検索">
        </div>
      </form>
      @if (isset($books))
        @component('components.books_list',['books'=>$books])
          @slot('page_path')
            book
          @endslot
          @slot('detail')
            detail
          @endslot
        @endcomponent
      @endif
    </div>
  </div>
@endsection

@section('footer')
copyright 2017 tuyano.
@endsection
