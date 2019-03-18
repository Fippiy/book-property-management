<html>
<head>
  <meta charset="utf-8">
  <title>@yield('title')</title>
  <link href="/css/reset.css" rel="stylesheet" type="text/css">
  <link href="/css/layout-header.css" rel="stylesheet" type="text/css">
  <!-- ページ毎スタイルの読み込み -->
  @yield('stylesheet')
</head>
<body>
  <!-- ページ全体 -->
  <div class="whole">

    <!-- ヘッダ(コンポーネント) -->
    @component('components.header')
    @endcomponent

    <!-- 各ページ内容表示 -->
    @yield('content')

  </div>
</body>
</html>
