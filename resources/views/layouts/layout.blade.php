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
    <!-- ヘッダ(コンポーネント) -->
    @if (Route::currentRouteName() == 'book.show')
      @component('components.header',['book'=>$book])
      @endcomponent
    @else
      @component('components.header')
      @endcomponent
    @endif

    <!-- 各ページ内容表示 -->
    @yield('content')

  </div>
</body>
</html>
