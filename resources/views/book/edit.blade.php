@extends('layouts.layout')

@section('title', 'EditForm')

@section('stylesheet')
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="/js/update_book.js" type="text/javascript" charset="UTF-8"></script>
  <link href="/css/sidebar.css" rel="stylesheet" type="text/css">
  <link href="/css/book-form.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('content')
  <div class="book-form-area">
    <!-- サイドバー(コンポーネント) -->
    @component('components.sidebar')
    @endcomponent
    <div>
      <form action="/book/{{$form->id}}" method="post" class="book-form" enctype="multipart/form-data">
        {{ csrf_field() }}
        <table>
        <input type="hidden" name="_method" value="PUT">
        <tr><th>タイトル</th><td><input type="text" name="title" value="{{$form->title}}" class="book-title"></td></tr>
        <tr><th>写真</th><td><input type="file" name="picture" value="{{$form->picture}}"></td></tr>
        <tr><th>写真削除</th><td><input type="button" name="delete" value="削除" class="delete-picture"></td></tr>
        <tr><th></th><td><input type="submit" value="send"></td></tr>
        </table>
      </form>
      <div class="index-content">
      <!-- <div> -->
        <div class="books-list">
          <div class="books-list__title">
            編集のイメージ
          </div>
          <div class="book-table">
            <table class="book-table__list">
              <tr>
                <th></th>
                <th>写真</th>
                <th>タイトル</th>
                <th>登録日</th>
              </tr>
              <tr>
                <td>編集後</td>
                <td class="afterimage">
                  @if (isset($form->picture))
                    <img src="{{$form->picture}}" width="100px">
                  @else
                    No Image
                  @endif
                </td>
                <td class="aftertitle">{{$form->title}}</td>
                <td>{{$form->created_at->format('y/m/d')}}</td>
              </tr>
              <tr>
                <td>編集前</td>
                <td>
                  @if (isset($form->picture))
                    <img src="{{$form->picture}}" width="100px">
                  @else
                    No Image
                  @endif
                </td>
                <td><a href="/book/{{$form->id}}">{{$form->title}}</a></td>
                <td>{{$form->created_at->format('y/m/d')}}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
