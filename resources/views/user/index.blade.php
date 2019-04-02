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

      <!-- テンプレート化予定 -->
      <div class="my-book__list">
        <div class="my-book__list--picture">
          <img src="../image/no-entry.jpg">
        </div>
        <div class="my-book__list--detail">
          <h3 class="book-title">仮置き一覧タイトルその1</h3>
        </div>
      </div>
      <!-- テンプレート化予定ここまで -->


    </div>
  </div>
@endsection
