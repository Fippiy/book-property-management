@extends('layouts.layout')

@section('title', 'MyBook')

@section('stylesheet')
  <link href="/css/menulist.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('property.index') }}
  </div>
@endsection

@section('pagemenu')
  @include('components.menu_property')
@endsection

@section('content')
  <div class="index-content">
    <div class="books-list">
      <div class="books-list__title propertypage-color">
        所有書籍一覧
      </div>
      @if (isset($books))
        @component('components.books_list',['books'=>$books])
          @slot('page_path')
            property
          @endslot
        @endcomponent
      @endif
    </div>
  </div>
@endsection
