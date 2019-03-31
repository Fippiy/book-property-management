@extends('layouts.layout')

@section('title', 'FindPage')

@section('stylesheet')
  <link href="/css/sidebar.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('content')
  <div class="index-content">
    <!-- サイドバー(コンポーネント) -->
    @component('components.sidebar')
    @endcomponent

    <div class="books-list">
      <div class="books-list__title">
        検索
      </div>
        <form class="book-find" action="/book/find" method="post">
          {{ csrf_field() }}
          <h2 class="book-find__word">検索ワードを入力して下さい</h2>
          <div class="book-find__input">
            <input type="text" class="book-find__input--text" name="input" value="{{$input}}">
            <input type="submit" class="book-find__input--submit" value="検索">
          </div>
        </form>
      @include('components.books_list')
    </div>
  </div>
@endsection

@section('footer')
copyright 2017 tuyano.
@endsection
