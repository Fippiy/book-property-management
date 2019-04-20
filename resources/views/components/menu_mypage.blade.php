<div class="menulist__menutab">
  <a href="/user">
    <div class="tab mypage-color">
      マイページトップ
    </div>
  </a>
  <a href="{{ route('user.edit', $auth->id)}}">
    <div class="tab mypage-color">
      ユーザ情報編集
    </div>
  </a>
  <div class="tab mypage-color">
    ユーザー情報削除
  </div>
</div>
