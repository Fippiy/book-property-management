@extends('layouts.layout')

@section('title', 'CreateForm')

@section('stylesheet')
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="/js/update_book.js" type="text/javascript" charset="UTF-8"></script>
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('book.create') }}
  </div>
@endsection

@section('pagemenu')
  @include('components.menu_book')
@endsection

@section('content')
  <div class="index-content">
    <div class="books-list">
      <div class="books-list__title bookpage-color">
        新規登録
      </div>
      <div class="book-new">
        <form action="/book" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="form-contents">
            <div class="form-left">
              <div>
                <div class="form-label">写真</div>
                <div><input type="file" name="picture"></div>
                <div class="form-input__picture afterimage"><span class="form-input__picture--text">写真が選択されていません</span></div>
              </div>
            </div>
            <div class="form-right">
              <div class="form-input">
                <div class="form-label">タイトル名</div>
                <div><input class="form-input__input" type="text" name="title" value="{{old('title')}}"></div>
              </div>
              <div class="form-input">
                <div class="form-label">詳細</div>
                <div><textarea class="form-input__detail" type="text" name="detail">{{old('detail')}}</textarea></div>
              </div>
            </div>
          </div>
          <div class="form-foot">
            <input class="send" type="submit" value="登録">
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
