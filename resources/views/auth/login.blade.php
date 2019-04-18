@extends('layouts.app')

@section('title', 'ユーザー認証')

@section('content')
<div class="auth-contents">
  <div class="auth-contents__message">
    @foreach ($errors->all() as $error)
      <p class="auth-contents__message--error">{{ $error }}</p>
    @endforeach
    @if (empty($error))
      <p class="auth-contents__message--message">ログイン情報を入力して下さい。</p>
    @endif
  </div>
  <div class="auth-contents__form">
    <form action="{{ route('login') }}" method="post">
      {{ csrf_field() }}
      <div class="form-group">
        <div class="form-group__lavel"><lavel>メールアドレス</lavel></div>
        <input class="form-group__input" type="text" name="email" value="{{ old('email') }}">
      </div>
      <div class="form-group">
        <div class="form-group__lavel"><lavel>パスワード</lavel><span class="form-group__pass"><a class="form-group__pass--color" href="{{ route('password.request') }}">パスワードを忘れた場合</a></span></div>
        <input class="form-group__input" type="password" name="password">
      </div>
      <div class="form-group">
        <input class="form-group__submit" type="submit" value="ログイン">
      </div>
    </form>
  </div>
</div>
@endsection
