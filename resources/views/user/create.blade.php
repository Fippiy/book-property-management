@extends('layouts.layout')

@section('title', 'CreateForm')

@section('stylesheet')
  <link href="/css/menulist.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('user.create') }}
  </div>
@endsection

@section('pagemenu')
  @include('components.menu_mypage')
@endsection

@section('content')
  <div class="index-content">
    <div class="books-list">
      <div class="books-list__title mypage-color">
        所有書籍登録
      </div>
      @if (isset($msg))
        <div class="books-list__msg">
            <span>{{$msg}}</span>
        </div>
      @endif
      <div class="book-new">
        @if (isset($books) && count($books) != 0)
          <form action="/user" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-contents">
              <div class="form-one-size">
                <div class="form-input">
                  <div class="form-label">タイトル名</div>
                  <select class="form-input__select" name="bookdata_id">
                    @foreach ($books as $book)
                      <option value="{{$book->id}}">{{$book->title}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-input">
                  <div class="form-label">所持数</div>
                  <div><input class="form-input__number" type="number" name="number" value="1"></div>
                </div>
                <div class="form-input">
                  <div class="form-label">所持日</div>
                  <div><input class="form-input__date" type="date" name="getdate" value="{{old('getdate')}}"></div>
                </div>
                <div class="form-input">
                  <div class="form-label">フリーメモ</div>
                  <div><textarea class="form-input__detail" type="text" name="freememo">{{old('freememo')}}</textarea></div>
                </div>
              </div>
            </div>
            <div class="form-foot">
              <input class="send" type="submit" value="登録">
            </div>
          </form>
        @else
          <span>全ての書籍が登録済みです。</span>
        @endif
      </div>
      @if (isset($property))
        @include('components.book_property_detail')
      @endif
    </div>
  </div>
@endsection
