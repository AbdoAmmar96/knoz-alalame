@props(['service', 'featured' => false])

<article @class(['card', 'card-img', 'card-featured' => $featured, 'rv'])>
  <div class="card-art">
    <img src="{{ img($service->image) }}" alt="{{ $service->title }}" loading="lazy" />
    @if($featured)<span class="card-badge">الأكثر طلباً</span>@endif
  </div>
  <div class="card-bd">
    <h3>{{ $service->title }}</h3><p>{{ $service->body }}</p>
    @if($service->features)
      <ul class="feats">@foreach($service->features as $f)<li>{{ $f }}</li>@endforeach</ul>
    @endif
    <a href="{{ route('services.show', $service) }}" class="link-or">تعرّف على التفاصيل <x-icon name="arrow" /></a>
  </div>
</article>
