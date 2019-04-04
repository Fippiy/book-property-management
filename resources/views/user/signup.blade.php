@extends('layouts.auth-layout')

@section('title', 'ユーザ情報登録')

@section('content')
<p>{{$message}}</p>
  <table>
    <form action="/user/signup" method="post">
      {{ csrf_field() }}
      <tr><th>name:</th><td><input type="text" name="name"></td></tr>
      <tr><th>mail:</th><td><input type="text" name="email"></td></tr>
      <tr><th>pass:</th><td><input type="password" name="password"></td></tr>
      <tr><th>repass:</th><td><input type="password" name="password_confirmation"></td></tr>
      <tr><th></th><td><input type="submit" value="send"></td></tr>
    </form>
  </table>
@endsection
