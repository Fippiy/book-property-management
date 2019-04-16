<html>
<head>
  <meta charset="utf-8">
  <title>@yield('title')</title>
  <link href="/css/reset.css" rel="stylesheet" type="text/css">
  <link href="/css/auth.css" rel="stylesheet" type="text/css">
</head>
<body>
  <!-- 各ページ内容表示 -->
  <header>
    <div class="head-var">
      <h1 class="head-var__title"><a href="/">@yield('title')</a></h1>
      <div class="head-var__select">
        <span class="head-var__select--box"><a href="{{ route('register') }}">新規登録</a></span>
        <span class="head-var__select--box"><a href="{{ route('login') }}">ログイン</a></span>
      </div>
    </div>
  </header>
  @yield('content')
</body>
</html>
