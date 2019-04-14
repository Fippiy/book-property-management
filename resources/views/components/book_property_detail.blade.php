<div class="book-detail">
  <div class="book-detail__picture">
    @if (isset($property->bookdata->picture))
      <img src="{{$property->bookdata->picture}}">
    @elseif (isset($property->bookdata->cover))
      <img src="{{$property->bookdata->cover}}">
    @else
      <img src="../image/no-entry.jpg">
      <br>写真は登録されていません。
    @endif
  </div>
  <div class="book-detail__document">
    <h3 class="document-index">所持書籍情報</h3>
    <div class="document-content">
      <div class="document-content__label">タイトル</div>
      <div class="document-content__column">{{$property->bookdata->title}}</div>
    </div>
    <div class="document-content">
      <div class="document-content__label">所持数</div>
      <div class="document-content__column">{{$property->number}}</div>
    </div>
    <div class="document-content">
      <div class="document-content__label">取得日</div>
      <div class="document-content__column">{{$property->getdate}}</div>
    </div>
    <div class="document-content">
      <div class="document-content__label">フリーメモ</div>
      <div class="document-content__column">{{$property->freememo}}</div>
    </div>
  </div>
</div>
