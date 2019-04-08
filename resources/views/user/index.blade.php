@extends('layouts.layout')

@section('title', 'MyPage')

@section('stylesheet')
  <link href="/css/mypage.css" rel="stylesheet" type="text/css">
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

  <div class="mypage-content">
    <div class="mypage-content__left">
      <div class="mypage-content__left--box">
        <h2 class="mypage-label__top">ユーザー情報</h2>
      </div>
      <div class="mypage-content__left--box">
        <h2 class="mypage-label">ユーザー名</h2>
        <span class="mypage-element">{{$user->name}}</span>
      </div>
    </div>
    <div class="mypage-content__right">
      <div class="my-book__title">
        所有書籍一覧
      </div>

      <div class="my-book__list">
        <div class="my-book__list--picture">
          <img src="../image/no-entry.jpg">
        </div>
        <div class="my-book__list--detail">
          <h3 class="book-title">{{$property->id}}</h3>
        </div>
      </div>


    </div>
  </div>
@endsection
