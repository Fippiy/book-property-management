<html>
<head>
  <meta charset="utf-8">
  <title>TopPage</title>
  <link href="/css/reset.css" rel="stylesheet" type="text/css">
  <link href="/css/book-base.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div class="index-content">
    <header class="index-content__header">
      <div class="index-content__header--headbar">
      </div>
      <div class="index-content__header--title">
        <h1 class="title-name">Book-property</h1>
      </div>
      <div class="index-content__header--breadcrumbs">
        トップページ > ライブラリー
      </div>
      <div class="index-content__header--menutab">
        <div class="tab mypage">
          マイページ
        </div>
        <div class="tab search">
          検索
        </div>
      </div>
    </header>
    <div class="index-content__contents">
      <div class="sidebar">
        <div class="sidebar__title">カテゴリ一覧</div>
        <ul class="sidebar__list">
          <li class="sidebar__list--name">カテゴリ1</li>
          <li class="sidebar__list--name">カテゴリ2</li>
          <li class="sidebar__list--name">カテゴリ3</li>
        </ul>
      </div>
      <div class="books-list">
        <div class="books-list__title">
          全タイトル
        </div>
        <div class="book-table">
          <table class="book-table__list">
            <tr>
              <td>id</td>
              <td>写真</td>
              <td>タイトル</td>
              <td>登録日</td>
            </tr>
            @foreach ($books as $book)
            <tr>
              <td>{{$book->id}}</td>
              <td>{{$book->picture}}</td>
              <td>{{$book->title}}</td>
              <td>{{$book->created_at}}</td>
            </tr>
            @endforeach
          </table>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
