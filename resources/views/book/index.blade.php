@extends('layouts.layout')

@section('title', 'TopPage')

@section('stylesheet')
  <link href="/css/menulist.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('book.index') }}
  </div>
@endsection

@section('pagemenu')
  @include('components.menu_book')
@endsection

@section('content')
  <div class="index-content">
    <div class="books-list">
      <div class="books-list__title bookpage-color">
        登録書籍一覧
      </div>
      @if (isset($books))
        @component('components.books_list',['books'=>$books])
          @slot('page_path')
            book
          @endslot
          @slot('detail')
            detail
          @endslot
          @slot('pagination')
            index
          @endslot
        @endcomponent
      @endif
    </div>
  </div>
@endsection
