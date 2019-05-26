<div class="book-table">
  @if (isset($books))
    @if ($page_path == 'book')
      <form action="{{ route('book.some_delete') }}" method="post">
    @else
      <form action="{{ route('property.some_delete') }}" method="post">
    @endif
      <div class="book-table__btn">
        <span>操作：</span>
        <input type="submit" class="book-table__btn--delete" value="書籍情報一括削除">
      </div>
      {{ csrf_field() }}
      @foreach ($books as $book)
        <div class="book-table__list">
          <div class="book-table__list--checkbox">
            <input type="checkbox" name="select_books[]" value="{{$book->id}}">
          </div>
          <div class="book-table__list--picture">
            <a href="/{{$page_path}}/{{$book->id}}">
              @if (isset($book->picture))
                <img src="{{$book->picture}}">
              @elseif (isset($book->cover))
                <img src="{{$book->cover}}">
              @else
                <img src="../image/no-entry.jpg">
              @endif
            </a>
          </div>
          <div class="book-table__list--detail">
            <a href="/{{$page_path}}/{{$book->id}}"><h3 class="list-book-title">{{$book->title}}</h3></a>
            <p class="list-book-detail">{{ str_limit($book->$detail, $limit = 300, $end = '...') }}</p>
          </div>
        </div>
      @endforeach
    </form>
  @endif
</div>
