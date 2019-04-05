@extends('layouts.auth-layout')

@section('title', 'ユーザー認証')

@section('content')
<div class="auth-contents">
  <div class="auth-contents__message">
    <p>{{$message}}</p>
  </div>
  <div class="auth-contents__form">
    <form action="/user/login" method="post">
      {{ csrf_field() }}
      <div class="form-group">
        <div class="form-group__lavel"><lavel>メールアドレス</lavel></div>
        <input class="form-group__input" type="text" name="email">
      </div>
      <div class="form-group">
        <div class="form-group__lavel"><lavel>パスワード</lavel></div>
        <input class="form-group__input" type="password" name="password">
      </div>
      <div class="form-group">
        <input class="form-group__submit" type="submit" value="ログイン">
      </div>
    </form>
  </div>
</div>
@endsection
