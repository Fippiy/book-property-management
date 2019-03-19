@extends('layouts.layout')

@section('title', 'TopPage')

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
        全タイトル
      </div>
      <div class="book-table">
        <table class="book-table__list">
          <tr>
            <td>id</td>
            <td>写真</td>
            <td>タイトル</td>
            <td>登録日</td>
          </tr>
          @foreach ($books as $book)
          <tr>
            <td>{{$book->id}}</td>
            <td>{{$book->picture}}</td>
            <td><a href="/book/{{$book->id}}">{{$book->title}}</a></td>
            <td>{{$book->created_at}}</td>
          </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
@endsection
