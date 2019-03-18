@extends('layouts.layout')

@section('title', 'AddBook')

@section('stylesheet')
  <link href="/css/sidebar.css" rel="stylesheet" type="text/css">
  <link href="/css/book-form.css" rel="stylesheet" type="text/css">
@endsection

@section('content')
  <div class="book-form-area">
    <!-- サイドバー(コンポーネント) -->
    @component('components.sidebar')
    @endcomponent
    <form action="/book" method="post" class="book-form">
      {{ csrf_field() }}
      <table>
      <tr><th>タイトル</th><td><input type="text" name="title" value="{{old('title')}}"></td></tr>
      <tr><th>写真</th><td><input type="text" name="picture" value="{{old('picture')}}"></td></tr>
      <tr><th></th><td><input type="submit" value="send"></td></tr>
      </table>
    </form>
  </div>
@endsection
