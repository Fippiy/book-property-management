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
            <th>id</th>
            <th>写真</th>
            <th>タイトル</th>
            <th>登録日</th>
          </tr>
          @foreach ($books as $book)
          <tr>
            <td>{{$book->id}}</td>
            <td>
              @if (isset($book->picture))
                <img src="/storage/book_images/{{$book->id}}.jpg">
              @else
                No Image
              @endif
            </td>
            <td><a href="/book/{{$book->id}}">{{$book->title}}</a></td>
            <td>{{$book->created_at->format('y/m/d')}}</td>
          </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
@endsection
