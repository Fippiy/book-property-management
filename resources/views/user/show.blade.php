@extends('layouts.layout')

@section('title', 'ShowPage')

@section('stylesheet')
  <link href="/css/menulist.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('user.show',$property) }}
  </div>
@endsection

@section('pagemenu')
  @include('components.menu_mypage')
@endsection

@section('content')
  <div class="index-content">
    <div class="books-list">
      <div class="books-list__title mypage-color">
        所有書籍詳細
        <div class="books-list__title--navigation">
          <a href="/user/{{$property->id}}/edit" class="nav-btn edit">編集</a>
          <form action="/user/{{$property->id}}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="DELETE">
            <input type="submit" class="nav-btn delete" value="削除">
          </form>
        </div>
      </div>

      <!-- ユーザー所有書籍詳細情報 -->
      <div class="book-detail">
        <div class="book-detail__picture">
          @if (isset($property->bookdata->picture))
            <img src="{{$property->bookdata->picture}}">
          @elseif (isset($property->bookdata->cover))
            <img src="{{$property->bookdata->cover}}">
          @else
            <img src="../image/no-entry.jpg">
            <br>写真は登録されていません。
          @endif
        </div>
        <div class="book-detail__document">
          <h3 class="document-index">所持書籍情報</h3>
          <div class="document-content">
            <div class="document-content__label">タイトル</div>
            <div class="document-content__column">{{$property->bookdata->title}}</div>
          </div>
          <div class="document-content">
            <div class="document-content__label">所持数</div>
            <div class="document-content__column">{{$property->number}}</div>
          </div>
          <div class="document-content">
            <div class="document-content__label">取得日</div>
            <div class="document-content__column">{{$property->getdate}}</div>
          </div>
          <div class="document-content">
            <div class="document-content__label">フリーメモ</div>
            <div class="document-content__column">{{$property->freememo}}</div>
          </div>
        </div>
      </div>


    </div>
  </div>
@endsection
