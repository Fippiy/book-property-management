@extends('layouts.layout')

@section('title', 'Delete')

@section('stylesheet')
  <link href="/css/menulist.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('user.delete') }}
  </div>
@endsection

@section('pagemenu')
  @include('components.menu_mypage')
@endsection

@section('content')
  <div class="index-content">
    <div class="books-list">
      <div class="books-list__title mypage-color">
        ユーザー情報削除
      </div>
      <div class="book-table">
        <div class="book-table__profile-list">
        <div class="profile-group">
            <div class="profile-group__element">ユーザー情報を削除するには、削除ボタンを押して下さい。</div>
          </div>
          <div class="profile-group">
            <div class="profile-group__title">ユーザーID</div>
            <div class="profile-group__element">{{$auth->id}}</div>
          </div>
          <div class="profile-group">
            <div class="profile-group__title">ユーザー名</div>
            <div class="profile-group__element">{{$auth->name}}</div>
          </div>
          <div class="profile-group">
            <div class="profile-group__title">メールアドレス</div>
            <div class="profile-group__element">{{$auth->email}}</div>
          </div>
          <div class="profile-group">
            <div class="profile-group__title">パスワード</div>
            <div class="profile-group__element">パスワードは安全の為表示できません。</div>
          </div>
          <div class="profile-group">
            <div class="profile-group__title">登録日時</div>
            <div class="profile-group__element">{{$auth->created_at}}</div>
          </div>
          <div class="profile-group">
            <div class="profile-group__title">最終更新日時</div>
            <div class="profile-group__element">{{$auth->updated_at}}</div>
          </div>
          <div class="book-new">
            <form action="{{ route('user.destroy', $auth->id)}}" method="post" enctype="multipart/form-data">
              <div class="form-foot">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="DELETE">
                <input class="send" type="submit" value="削除">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
