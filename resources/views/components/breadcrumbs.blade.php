@if (count($breadcrumbs))
  <ol class="bread-area">
    @foreach ($breadcrumbs as $breadcrumb)
      @if ($breadcrumb->url && !$loop->last)
        <li class="bread-area__item"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
        <li class="bread-area__item">/</li>
      @else
        <li class="bread-area__item active">{{ $breadcrumb->title }}</li>
      @endif
    @endforeach
  </ol>
@endif
