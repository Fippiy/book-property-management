@extends('layouts.layout')

@section('title', 'ISBN')

@section('stylesheet')
<link href="/css/menulist.css" rel="stylesheet" type="text/css">
  <link href="/css/book-index.css" rel="stylesheet" type="text/css">
@endsection

@section('breadcrumbs')
  <div class="book-header__breadcrumbs">
    {{ Breadcrumbs::render('book.isbn') }}
  </div>
@endsection

@section('pagemenu')
  @include('components.menu_book')
@endsection

@section('content')
  <div class="index-content">
    <div class="books-list">
      <div class="books-list__title bookpage-color">
        ISBNコード登録
      </div>
      <div class="books-list__msg">
        @foreach ($errors->all() as $error)
        <p class="auth-contents__message--error">{{ $error }}</p>
        @endforeach
      </div>
      <div class="book-new">
        <form action="/book/isbn_some" method="post">
          {{ csrf_field() }}
          <div class="form-contents">
            <div class="form-input form-one-size">
              <div class="form-label">ISBNコード</div>
              <!-- <div><input class="form-input__input" type="number" name="isbn1" value="9784756918765"></div> -->
              <!-- @for ($i = 0; $i < 10; $i++)
                <div><input class="form-input__input" type="number" name="isbn{{$i}}" value="9784756918765"></div>
              @endfor -->
              <div><input class="form-input__input" type="number" name="isbn0" value="9784797398892"></div>
              <div><input class="form-input__input" type="number" name="isbn1" value="9784756918765"></div>
              <div><input class="form-input__input" type="number" name="isbn2" value="9784844366454"></div>
              <div><input class="form-input__input" type="number" name="isbn3" value="9784798052588"></div>
              <div><input class="form-input__input" type="number" name="isbn4" value="9784863542174"></div>
              <div><input class="form-input__input" type="number" name="isbn5" value="9784054066892"></div>
              <div><input class="form-input__input" type="number" name="isbn6" value="9784756918765"></div>
              <div><input class="form-input__input" type="number" name="isbn7" value="9784756918765"></div>
              <div><input class="form-input__input" type="number" name="isbn8" value=""></div>
              <div><input class="form-input__input" type="number" name="isbn9" value=""></div>
            </div>
          </div>
          <div class="form-foot">
            <input class="send isbn" type="submit" value="登録">
          </div>
        </form>
      </div>
      <div class="book-new">
      </div>
    </div>
  </div>
  @if (isset($answers))
    @foreach ($answers as $answer)
      {{$answer['isbn']}}
      {{$answer['msg']}}
      @if (isset($answer['result']))
        {{$answer['result']}}
      @endif
      <br><br>
    @endforeach
  @endif
 @endsection
