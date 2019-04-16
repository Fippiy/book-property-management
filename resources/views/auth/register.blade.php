@extends('layouts.app')

@section('title', 'ユーザー情報登録')

@section('content')
<div class="auth-contents">
  <div class="auth-contents__message">
    @foreach ($errors->all() as $error)
      <p class="auth-contents__message--error">{{ $error }}</p>
    @endforeach
    @if (empty($error))
      <p class="auth-contents__message--message">登録して下さい。</p>
    @endif
</div>
  <div class="auth-contents__form">
    <form action="{{ route('register') }}" method="post">
      {{ csrf_field() }}
      <div class="form-group">
        <div class="form-group__lavel"><lavel>ユーザー名</lavel></div>
        <input class="form-group__input" type="text" name="name" value="{{ old('name') }}">
      </div>
      <div class="form-group">
        <div class="form-group__lavel"><lavel>メールアドレス</lavel></div>
        <input class="form-group__input" type="text" name="email" value="{{ old('email') }}">
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
