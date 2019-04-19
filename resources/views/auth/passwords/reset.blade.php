@extends('layouts.app')

@section('title', 'パスワード再設定')

@section('content')
<div class="auth-contents">
  <div class="auth-contents__message">

  @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
  @else
    <p class="auth-contents__message--message">新しいパスワードを設定します。</p>
    @foreach ($errors->all() as $error)
      <p class="auth-contents__message--error">{{ $error }}</p>
    @endforeach

  @endif
  </div>
  <div class="auth-contents__form">
    <form action="{{ route('password.update') }}" method="post">
      {{ csrf_field() }}
      <input type="hidden" name="token" value="{{ $token }}">
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
        <input class="form-group__submit" type="submit" value="送信">
      </div>
    </form>
  </div>
</div>
@endsection
