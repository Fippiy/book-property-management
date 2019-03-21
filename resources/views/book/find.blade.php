@extends('layouts.layout')

@section('title', 'FindPage')

@section('stylesheet')
  <link href="/css/sidebar.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('content')
  <div class="index-content">
    <!-- サイドバー(コンポーネント) -->
    @component('components.sidebar')
    @endcomponent
    <div class="books-list">
      <div class="books-list__title">
        検索ページ
      </div>
    <form action="/book/find" method="post">
      {{ csrf_field() }}
      <input type="text" name="input" value="{{$input}}">
      <input type="submit" value="find">
    </form>
      <div class="book-table">
        <table class="book-table__list">
          <tr>
            <td>id</td>
            <td>写真</td>
            <td>タイトル</td>
            <td>登録日</td>
          </tr>
          @if (isset($books))
            @foreach ($books as $book)
              <tr>
                <td>{{$book->id}}</td>
                <td>
                  @if (isset($book->picture))
                    <img src="/storage/book_images/{{$book->picture}}">
                  @else
                    No Image
                  @endif
                </td>
                <td><a href="/book/{{$book->id}}">{{$book->title}}</a></td>
                <td>{{$book->created_at}}</td>
              </tr>
            @endforeach
          @endif
        </table>
      </div>
    </div>
  </div>
@endsection

@section('footer')
copyright 2017 tuyano.
@endsection
