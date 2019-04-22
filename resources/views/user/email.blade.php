@extends('layouts.layout')

@section('title', 'EditForm')

@section('stylesheet')
  <link href="/css/menulist.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('edit.user',$auth) }}
  </div>
@endsection

@section('pagemenu')
  @include('components.menu_mypage')
@endsection

@section('content')
<div class="index-content">
    <div class="books-list">
      <div class="books-list__title mypage-color">
        ユーザー情報編集
      </div>
      <div class="books-list__msg">
        @foreach ($errors->all() as $error)
          <p class="auth-contents__message--error">{{ $error }}</p>
        @endforeach
        @if (empty($error))
          <p class="auth-contents__message--message">変更するデータを入力してください。</p>
        @endif
      </div>
      <div class="book-new">
        {{ route('email.change')}}
        <form action="{{ route('email.change')}}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
          <div class="form-contents">
            <div class="form-one-size">
              <div class="form-input">
                <div class="form-label">メールアドレス</div>
                <div><input class="form-input__input" type="text" name="email" value="{{$auth->email}}"></div>
              </div>
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
