@props(['sector'])

<article class="sector rv">
  <img class="sector-bg" src="{{ img($sector->image) }}" alt="{{ $sector->title }}" loading="lazy" />
  <span class="s-num">{{ $sector->number }}</span>
  <div class="sector-in">
    <div class="ic">{!! $sector->icon !!}</div>
    <h3>{{ $sector->title }}</h3><p>{{ $sector->body }}</p>
  </div>
</article>
