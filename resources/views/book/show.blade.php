@extends('layouts.layout')

@section('title', 'ShowPage')

@section('stylesheet')
  <link href="/css/menulist.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('book.show',$book) }}
  </div>
@endsection

@section('pagemenu')
  @include('components.menu_book')
@endsection

@section('content')
  <div class="index-content">
    <div class="books-list">
      <div class="books-list__title bookpage-color">
        詳細
        <div class="books-list__title--navigation">
          <a href="/book/{{$book->id}}/edit" class="nav-btn edit">編集</a>
          <form action="/book/{{$book->id}}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="DELETE">
            <input type="submit" class="nav-btn delete" value="削除">
          </form>
        </div>
      </div>
      @foreach ($errors->all() as $error)
      <div class="books-list__msg">
        <p class="auth-contents__message--error">{{ $error }}</p>
      </div>
      @endforeach
      @include('components.book_detail')
    </div>
  </div>
@endsection
