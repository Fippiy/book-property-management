<div class="books-list">
  <div class="books-list__title">
    {{$form_title}}
  </div>
  @if($action == 'find' || $action == 'search')
    <form class="book-find" action="/book/find" method="post">
      {{ csrf_field() }}
      <h2 class="book-find__word">検索ワードを入力して下さい</h2>
      <div class="book-find__input">
        <input type="text" class="book-find__input--text" name="input" value="{{$input}}">
        <input type="submit" class="book-find__input--submit" value="検索">
      </div>
    </form>
  @endif
  <div class="book-table">
    @if (isset($books))
      @foreach ($books as $book)
        <div class="book-table__list">
          <div class="book-table__list--picture">
            <a href="/book/{{$book->id}}">
              @if (isset($book->picture))
                <img src="{{$book->picture}}">
              @else
                <img src="../image/no-entry.jpg">
              @endif
            </a>
          </div>
          <div class="book-table__list--detail">
            <h3 class="book-title">{{$book->title}}</h3>
            <span class="input-date">{{$book->created_at->format('y/m/d')}}</span>
          </div>
        </div>
      @endforeach
    @endif
    </table>
  </div>
</div>
