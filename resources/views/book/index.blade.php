@extends('layouts.layout')

@section('title', 'TopPage')

@section('stylesheet')
  <link href="/css/sidebar.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('book.index') }}
  </div>
@endsection

@section('content')
  <!-- サブメニュー(コンポーネント) -->
s  @component('components.menu_book')
  @endcomponent
  <div class="index-content">
    <div class="books-list">
      <div class="books-list__title">
        登録書籍一覧
      </div>
      @include('components.books_list')
    </div>

  </div>
@endsection
