<div class="nav">
  <div class="nav__name">{{$name}}</div>
  <div class="nav__property">所有書籍[{{$user_book_count}}]</div>
  <form class="logout-form" action="{{ route('logout') }}" method="POST">
    {{ csrf_field() }}
    <div class="form-group">
      <input class="form-group__submit" type="submit" value="ログアウト">
    </div>
  </form>
</div>
