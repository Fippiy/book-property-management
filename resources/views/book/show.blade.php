@extends('layouts.layout')

@section('title', 'ShowPage')

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
        詳細ページ
        <div class="books-list__title--navigation">
          <a href="/book/{{$book->id}}/edit" class="nav-btn edit">編集</a>
          <form action="/book/{{$book->id}}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="DELETE">
            <input type="submit" class="nav-btn delete" value="削除">
          </form>
        </div>
      </div>
      <div class="book-detail">
        <div class="book-detail__picture">
          @if (isset($book->picture))
            <img src="{{$book->picture}}">
          @else
            No Image
          @endif
        </div>
        <div class="book-detail__document">
          <h3 class="book-detail__document--title">{{$book->title}}</h3>
        </div>
      </div>
    </div>
  </div>
@endsection
