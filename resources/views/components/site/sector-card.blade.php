@props(['sector'])

<article class="sector rv">
  <div class="sector-art">
    <img src="{{ img($sector->image) }}" alt="{{ $sector->title }}" loading="lazy" />
  </div>
  <div class="sector-in">
    <div class="ic">{!! $sector->icon !!}</div>
    <h3>{{ $sector->title }}</h3><p>{{ $sector->body }}</p>
  </div>
</article>
