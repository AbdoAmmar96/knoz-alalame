@props([
    'title',
    'eyebrow' => null,
    'image' => null,
    'crumb' => null,
    'lead' => null,
    'tags' => ['معاينة مجانية', 'ضمان مكتوب', 'استجابة 24/7'],
])

{{-- هيرو الصفحات الداخلية: نفس خلفية هيرو الرئيسية النظيفة، بمحتوى في المنتصف وبلا مصعد --}}
<section class="page-hero">
  <div class="hero-glow"></div>
  <div class="wrap rv">
    <div class="ph-in">
      @if($eyebrow)<span class="eyebrow">{{ $eyebrow }}</span>@endif
      <h1>{{ $title }}</h1>
      @if($lead)<p class="lead">{{ $lead }}</p>@endif
      @if($tags)
        <div class="ph-tags">
          @foreach($tags as $tag)<span>{{ $tag }}</span>@endforeach
        </div>
      @endif
      <div class="crumbs"><a href="{{ route('home') }}">الرئيسية</a><span>—</span><b>{{ $crumb ?? $title }}</b></div>
    </div>
  </div>
</section>
