@extends('layouts.layout')

@section('title', 'MyPage')

@section('stylesheet')
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('user.index') }}
  </div>
@endsection

@section('content')
  <!-- サブメニュー(コンポーネント) -->
  @component('components.menu_mypage')
  @endcomponent
  <div class="index-content">
    <div class="books-list">
      <div class="books-list__title">
        所有書籍一覧
      </div>
      @include('components.books_list')
    </div>

  </div>
@endsection
