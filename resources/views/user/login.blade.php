@extends('layouts.auth-layout')

@section('title', 'ユーザ認証')

@section('content')
<p>{{$message}}</p>
  <table>
    <form action="/user/login" method="post">
      {{ csrf_field() }}
      <tr><th>mail:</th><td><input type="text" name="email"></td></tr>
      <tr><th>pass:</th><td><input type="password" name="password"></td></tr>
      <tr><th></th><td><input type="submit" value="send"></td></tr>
    </form>
  </table>
@endsection
