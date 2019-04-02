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
          <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <a class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>
          </form>
        </div>
      </div>
      <!-- パンくずは各ページにて個別処理 -->
      @yield('breadcrumbs')

      <!-- サイト内全体メニュー（コンポーネント） -->
      @component('components.menu_grand')
      @endcomponent
    </header>

    <!-- 各ページ内容表示 -->
    @yield('content')

  </div>
</body>
</html>
