@props(['quote'])

<article class="quote rv">
  <div class="qm">&#8221;</div>
  <div class="stars">@for($i = 0; $i < $quote->rating; $i++)<x-icon name="star" />@endfor</div>
  <p class="txt">{{ $quote->body }}</p>
  <div class="who"><span class="av">{{ mb_substr($quote->name, 0, 1) }}</span><div><b>{{ $quote->name }}</b><small>{{ $quote->role }}</small></div></div>
</article>
