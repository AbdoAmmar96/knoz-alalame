@props(['title', 'eyebrow' => null, 'image' => null, 'crumb' => null])

<section class="page-hero">
  @if($image)<img class="ph-bg" src="{{ img($image) }}" alt="" />@endif
  <span class="ph-fx"></span>
  <div class="wrap rv">
    @if($eyebrow)<span class="eyebrow">{{ $eyebrow }}</span>@endif
    <h1>{{ $title }}</h1>
    <div class="crumbs"><a href="{{ route('home') }}">الرئيسية</a><span>—</span><b>{{ $crumb ?? $title }}</b></div>
  </div>
</section>
