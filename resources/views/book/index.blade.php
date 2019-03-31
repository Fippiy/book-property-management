@extends('layouts.layout')

@section('title', 'TopPage')

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
        全タイトル
      </div>
      @include('components.books_list')
    </div>

  </div>
@endsection
