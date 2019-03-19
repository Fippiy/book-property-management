@extends('layouts.layout')

@section('title', 'EditForm')

@section('stylesheet')
  <link href="/css/sidebar.css" rel="stylesheet" type="text/css">
  <link href="/css/book-form.css" rel="stylesheet" type="text/css">
@endsection

@section('content')
  <div class="book-form-area">
    <!-- サイドバー(コンポーネント) -->
    @component('components.sidebar')
    @endcomponent
    <form action="/book/{{$form->id}}" method="post" class="book-form">
      {{ csrf_field() }}
      <table>
      <input type="hidden" name="_method" value="PUT">
      <tr><th>タイトル</th><td><input type="text" name="title" value="{{$form->title}}"></td></tr>
      <tr><th>写真</th><td><input type="text" name="picture" value="{{$form->picture}}"></td></tr>
      <tr><th></th><td><input type="submit" value="send"></td></tr>
      </table>
    </form>
  </div>
@endsection
