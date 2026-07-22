{{-- هيرو الرئيسية: بئر المصعد المتحرك بالكامل CSS (لا صور) --}}
@props(['stats'])

<section class="hero">
  <div class="hero-glow"></div>
  <div class="wrap hero-grid">
    <div class="hero-copy rv">
      <span class="eyebrow">{{ setting('hero_eyebrow') }}</span>
      <h1 class="h-xl"><span class="line">{{ setting('hero_title_1') }}</span><span class="line or">{{ setting('hero_title_2') }}</span></h1>
      <p class="lead">{{ setting('hero_lead') }}</p>
      <div class="btn-row hero-cta">
        <a href="{{ route('contact') }}" class="btn btn-primary">اطلب عرض سعر مجاني <x-icon name="arrow" /></a>
        <a href="{{ route('services') }}" class="btn btn-ghost">تصفّح خدماتنا</a>
      </div>
      <div class="hero-stats">
        @foreach($stats->take(3) as $stat)
          <div class="st">
            <span class="num">@if($stat->is_counter)<span data-count="{{ $stat->value }}">0</span>{{ $stat->suffix }}@else{{ $stat->value }}@endif</span>
            <span class="lbl">{{ $stat->label }}</span>
          </div>
        @endforeach
      </div>
    </div>
    <div class="shaft-wrap rv">
      <div class="shaft-led"><span class="tri"></span><span class="digits"><i>1</i><i>2</i><i>3</i><i>4</i></span></div>
      <div class="shaft">
        <span class="rail a"></span><span class="rail b"></span>
        <div class="floors">
          <span class="fl"><i></i>01</span>
          <span class="fl"><i></i>02</span>
          <span class="fl"><i></i>03</span>
          <span class="fl"><i></i>04</span>
        </div>
        <div class="cabin"><span class="cd l"></span><span class="cd r"></span></div>
      </div>
      <div class="shaft-chip"><x-icon name="clock" /><div><b>24/7</b><small>استجابة لأعطال الطوارئ</small></div></div>
    </div>
  </div>
  <div class="scroll-cue"><span class="mouse"></span><span>انتقل للأسفل</span></div>
</section>
