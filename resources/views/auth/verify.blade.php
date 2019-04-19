@extends('layouts.app')

@section('title', '登録受付完了')

@section('content')
<div class="auth-contents">
  <div class="auth-contents__message">
    @if (session('resent'))
    <div class="auth-contents__message--message" role="alert">メールを再送しました。</div>
    @endif
    <div class="auth-contents__message--message">新規登録を受け付けました。</div>
    <div class="auth-contents__message--message">登録したメールアドレス宛にメールを送信しました、メールを確認して登録を完了させて下さい。</div>
    <div class="auth-contents__message--message">
      メールが送付されていない場合
      <a href="{{ route('verification.resend') }}">こちらをクリック</a>
      することで、再送できます。
    </div>
  </div>
</div>
@endsection
