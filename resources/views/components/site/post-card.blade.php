@props(['post'])

<a class="a-card rv" href="{{ $post->url }}" @if($post->is_external) target="_blank" rel="noopener" @endif>
  <div class="thumb"><img src="{{ img($post->image) }}" alt="{{ $post->title }}" loading="lazy" /></div>
  <div class="bd">
    @if($post->tag)<span class="tag">{{ $post->tag }}</span>@endif
    <h3>{{ $post->title }}</h3><p>{{ $post->excerpt }}</p>
    <span class="link-or">اقرأ المقال <x-icon name="arrow" /></span>
  </div>
</a>
