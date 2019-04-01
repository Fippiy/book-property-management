@extends('layouts.layout')

@section('title', 'ShowPage')

@section('stylesheet')
  <link href="/css/sidebar.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('content')
  <!-- サイドバー(コンポーネント) -->
  @component('components.menu_book')
  @endcomponent
  <div class="index-content">
    <div class="books-list">
      <div class="books-list__title">
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
      <div class="book-detail">
        <div class="book-detail__picture">
          @if (isset($book->picture))
            <img src="{{$book->picture}}">
          @else
            <img src="../image/no-entry.jpg">
            <br>写真は登録されていません。
          @endif
        </div>
        <div class="book-detail__document">
          <h3 class="document-index">書籍情報</h3>
          <div class="document-content">
            <div class="document-content__label">タイトル</div>
            <div class="document-content__column">{{$book->title}}</div>
          </div>
          <div class="document-content">
            <div class="document-content__label">著者名</div>
            <div class="document-content__column">テスト(仮置)</div>
          </div>
          <div class="document-content">
            <div class="document-content__label">出版社</div>
            <div class="document-content__column">テスト(仮置)</div>
          </div>
          <div class="document-content">
            <div class="document-content__label">出版日</div>
            <div class="document-content__column">1900/1/1(仮置)</div>
          </div>
          <div class="document-content">
            <div class="document-content__label">登録日</div>
            <div class="document-content__column">{{$book->created_at}}</div>
          </div>
          <div class="document-content">
            <div class="document-content__label">編集日</div>
            <div class="document-content__column">{{$book->updated_at}}</div>
          </div>
          <h4 class="document-index">書籍詳細</h4>
          <div class="document-content">
            <div class="document-content__column">{{$book->detail}}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
