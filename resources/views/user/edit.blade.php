@extends('layouts.layout')

@section('title', 'EditForm')

@section('stylesheet')
  <link href="/css/menulist.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('user.edit',$auth) }}
  </div>
@endsection

@section('pagemenu')
  @include('components.menu_mypage')
@endsection

@section('content')
@foreach ($errors->all() as $error)
  <p class="auth-contents__message--error">{{ $error }}</p>
@endforeach
<div class="index-content">
    <div class="books-list">
      <div class="books-list__title mypage-color">
        ユーザー情報編集
      </div>
      @if (isset($msg))
        <div class="books-list__msg">
            <span>{{$msg}}</span>
        </div>
      @endif
      <div class="book-new">
        <form action="{{ route('update.user', $auth->id)}}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" name="page" value="{{$page}}">
          <div class="form-contents">
            <div class="form-one-size">
              @if ($page == 'name')
              <div class="form-input">
                <div class="form-label">ユーザー名</div>
                <div><input class="form-input__input" type="text" name="name" value="{{$auth->name}}"></div>
              </div>
              @endif
              @if ($page == 'email')
              <div class="form-input">
                <div class="form-label">メールアドレス</div>
                <div><input class="form-input__input" type="text" name="email" value="{{$auth->email}}"></div>
              </div>
              @endif
              @if ($page == 'password')
              <div class="form-input">
                <div class="form-label">現在のパスワード</div>
                <div><input class="form-input__input" type="password" name="old_password" value=""></div>
              </div>
              <div class="form-input">
                <div class="form-label">新パスワード</div>
                <div><input class="form-input__input" type="password" name="password" value=""></div>
              </div>
              <div class="form-input">
                <div class="form-label">新パスワード(確認)</div>
                <div><input class="form-input__input" type="password" name="password_confirmation" value=""></div>
              </div>
              @endif
            </div>
          </div>
          <div class="form-foot">
            <input class="send" type="submit" value="編集">
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
