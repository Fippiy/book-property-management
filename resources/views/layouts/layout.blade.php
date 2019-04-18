<html>
<head>
  <meta charset="utf-8">
  <title>@yield('title')</title>
  <link href="/css/reset.css" rel="stylesheet" type="text/css">
  <link href="/css/whole.css" rel="stylesheet" type="text/css">
  <link href="/css/book-header.css" rel="stylesheet" type="text/css">
  <!-- ページ毎スタイルの読み込み -->
  @yield('stylesheet')
</head>
<body>
  <!-- ページ全体 -->
  <div class="whole">
    <!-- ヘッダ部 -->
    <header class="book-header">
      <div class="book-header__headbar">
      </div>
      <div class="book-header__header">
        <div class="book-header__header--title">
          <a href="/book"><h1 class="title-name">Book-property</h1></a>
        </div>
        <div class="book-header__header--navbar">
          @component('components.user_nav')
          @endcomponent
        </div>
      </div>
      <!-- パンくずは各ページにて個別処理 -->
      @yield('breadcrumbs')
    </header>

    <!-- メニュー -->
    <div class="menulist">
      <!-- サイト内全体メニュー（コンポーネント） -->
      @component('components.menu_grand')
      @endcomponent
      <!-- 各ページメニュー -->
      @yield('pagemenu')
    </div>

    <!-- 各ページ内容表示 -->
    @yield('content')

  </div>
</body>
</html>
