<?php $action = explode('@', Route::currentRouteAction())[1]; ?>
@extends('layouts.layout')

@section('title', 'TopPage')

@section('stylesheet')
  <link href="/css/sidebar.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('content')
  <?php echo $action ?>
  <div class="index-content">
    <!-- サイドバー(コンポーネント) -->
    @component('components.sidebar')
    @endcomponent
    @include('components.book',['form_title' => '全タイトル','action' => $action])

  </div>
@endsection
