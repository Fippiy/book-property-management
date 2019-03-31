<?php $action = explode('@', Route::currentRouteAction())[1]; ?>
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
    @include('components.book',['form_title' => '検索','action' => $action])
  </div>
@endsection

@section('footer')
copyright 2017 tuyano.
@endsection
