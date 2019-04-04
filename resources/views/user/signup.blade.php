@extends('layouts.auth-layout')

@section('title', 'ユーザー情報登録')

@section('content')
<div class="auth-contents">
  <div class="auth-contents__message">
    <p>{{$message}}</p>
  </div>
  <div class="auth-contents__form">
    <form action="/user/signup" method="post">
      {{ csrf_field() }}
      <div class="form-group">
        <div class="form-group__lavel"><lavel>ユーザー名</lavel></div>
        <input class="form-group__input" type="text" name="name">
      </div>
      <div class="form-group">
        <div class="form-group__lavel"><lavel>メールアドレス</lavel></div>
        <input class="form-group__input" type="text" name="email">
      </div>
      <div class="form-group">
        <div class="form-group__lavel"><lavel>パスワード</lavel></div>
        <input class="form-group__input" type="password" name="password">
      </div>
      <div class="form-group">
        <div class="form-group__lavel"><lavel>パスワード（確認）</lavel></div>
        <input class="form-group__input" type="password" name="password_confirmation">
      </div>
      <div class="form-group">
        <input class="form-group__submit" type="submit" value="新規登録">
      </div>
    </form>
  </div>

</div>
@endsection
