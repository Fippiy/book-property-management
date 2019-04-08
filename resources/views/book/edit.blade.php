@extends('layouts.layout')

@section('title', 'EditForm')

@section('stylesheet')
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="/js/update_book.js" type="text/javascript" charset="UTF-8"></script>
  <link href="/css/sidebar.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
  <link href="/css/label.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('book.edit',$form) }}
  </div>
@endsection

@section('content')
  <!-- サブメニュー(コンポーネント) -->
  @component('components.menu_book')
  @endcomponent
  <div class="index-content">
    <div class="books-list">
      <div class="books-list__title">
        編集
      </div>
      <div class="book-new">
        <form action="/book/{{$form->id}}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <input type="hidden" name="_method" value="PUT">
          <div class="form-contents">
            <div class="form-left">
              <div>
                <div class="form-label">写真</div>
                <div>
                  <input type="file" name="picture" value="{{$form->picture}}">
                  <input type="button" name="delete" value="削除" class="delete-picture">
                </div>
                <div class="form-input__picture afterimage">
                  @if (isset($form->picture))
                    <img src="{{$form->picture}}" width="100px">
                  @else
                    <span class="form-input__picture--text">写真が登録されていません</span>
                  @endif
                </div>
              </div>
            </div>
            <div class="form-right">
              <div class="form-input">
                <lavel class="form-label">タイトル名</lavel><span class="label__important">必須</span>
                <input class="form-input__input" type="text" name="title" value="{{$form->title}}">
              </div>
              <div class="form-input">
                <lavel class="form-label">詳細</lavel>
                <textarea class="form-input__detail" type="text" name="detail">{{$form->detail}}</textarea>
              </div>
            </div>
          </div>
          <div class="form-foot">
            <input class="send" type="submit" value="更新">
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection
