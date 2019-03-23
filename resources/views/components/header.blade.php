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
  <div class="book-header__breadcrumbs">
    トップページ > ライブラリー
  </div>
  <div class="book-header__menutab">
    <div class="tab mypage">
      マイページ
    </div>
    <a href="/book/find">
      <div class="tab search">
        検索
      </div>
    </a>
    <a href="/book/create">
      <div class="tab entry">
        新規登録
      </div>
    </a>
  </div>
</header>
