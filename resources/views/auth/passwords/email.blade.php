@extends('layouts.app')

@section('title', 'パスワードリセット')

@section('content')
<div class="auth-contents">
  <div class="auth-contents__message">

  @if (session('status'))
    <div class="alert alert-success" role="alert">
      {{ session('status') }}
    </div>
  @else
    <p class="auth-contents__message--message">パスワード変更します、登録したメールアドレスを入力してください。</p>
    <p class="auth-contents__message--message">※確認の為のメールを送信します。</p>
    @if ($errors->has('email'))
      <span class="auth-contents__message--message" role="alert">
        <strong>{{ $errors->first('email') }}</strong>
      </span>
    @endif
  @endif
  </div>
  <div class="auth-contents__form">
    <form action="{{ route('password.email') }}" method="post">
      {{ csrf_field() }}
      <div class="form-group">
        <div class="form-group__lavel"><lavel>メールアドレス</lavel></div>
        <input class="form-group__input" type="text" name="email" value="{{ old('email') }}">
      </div>
      <div class="form-group">
        <input class="form-group__submit" type="submit" value="送信">
      </div>
    </form>
  </div>
</div>
@endsection
