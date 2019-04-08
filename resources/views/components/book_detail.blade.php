<div class="book-detail">
  <div class="book-detail__picture">
    @if (isset($book->picture))
      <img src="{{$book->picture}}">
    @elseif (isset($book->cover))
      <img src="{{$book->cover}}">
    @else
      <img src="../image/no-entry.jpg">
      <br>写真は登録されていません。
    @endif
  </div>
  <div class="book-detail__document">
    <h3 class="document-index">書籍情報</h3>
    <div class="document-content">
      <div class="document-content__label">タイトル</div>
      <div class="document-content__column">{{$book->title}}</div>
    </div>
    <div class="document-content">
      <div class="document-content__label">シリーズ</div>
      <div class="document-content__column">{{$book->series}}</div>
    </div>
    <div class="document-content">
      <div class="document-content__label">ISBN</div>
      <div class="document-content__column">{{$book->isbn}}</div>
    </div>
    <div class="document-content">
      <div class="document-content__label">著者名</div>
      <div class="document-content__column">{{$book->author}}</div>
    </div>
    <div class="document-content">
      <div class="document-content__label">出版社</div>
      <div class="document-content__column">{{$book->publisher}}</div>
    </div>
    <div class="document-content">
      <div class="document-content__label">出版日</div>
      <div class="document-content__column">{{$book->pubdate}}</div>
    </div>
    <div class="document-content">
      <div class="document-content__label">登録日</div>
      <div class="document-content__column">{{$book->created_at}}</div>
    </div>
    <div class="document-content">
      <div class="document-content__label">編集日</div>
      <div class="document-content__column">{{$book->updated_at}}</div>
    </div>
    <h4 class="document-index">書籍詳細</h4>
    <div class="document-content">
      <div class="document-content__column">{{$book->detail}}</div>
    </div>
  </div>
</div>

