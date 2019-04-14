@extends('layouts.layout')

@section('title', 'EditForm')

@section('stylesheet')
  <link href="/css/menulist.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('user.edit',$form) }}
  </div>
@endsection

@section('pagemenu')
  @include('components.menu_mypage')
@endsection

@section('content')
<div class="index-content">
    <div class="books-list">
      <div class="books-list__title mypage-color">
        所有書籍編集
      </div>
      @if (isset($msg))
        <div class="books-list__msg">
            <span>{{$msg}}</span>
        </div>
      @endif
      <div class="book-new">
          <form action="/user/{{$form->id}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            <div class="form-contents">
              <div class="form-one-size">
                <div class="form-input">
                  <div class="form-label">タイトル名</div>
                  <span class="form-input__input">{{$form->bookdata->title}}</span>
                </div>
                <div class="form-input">
                  <div class="form-label">所持数</div>
                  <div><input class="form-input__input" type="number" name="number" value="{{$form->number}}"></div>
                </div>
                <div class="form-input">
                  <div class="form-label">所持日</div>
                  <div><input class="form-input__input" type="date" name="getdate" value="{{$form->getdate}}"></div>
                </div>
                <div class="form-input">
                  <div class="form-label">フリーメモ</div>
                  <div><textarea class="form-input__detail" type="text" name="freememo">{{$form->freememo}}</textarea></div>
                </div>
              </div>
            </div>
            <div class="form-foot">
              <input class="send" type="submit" value="登録">
            </div>
          </form>
      </div>
      @if (isset($property))
        @include('components.book_property_detail')
      @endif
    </div>
  </div>
@endsection
