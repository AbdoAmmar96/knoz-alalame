<x-layouts.site :seo="$seo">

<x-site.page-hero :title="$service->title" eyebrow="خدماتنا" :image="$service->image" :crumb="$service->title" />

<section class="sec">
  <div class="wrap about-grid">
    <div class="about-pic rv">
      <img src="{{ img($service->image) }}" alt="{{ $service->title }}" />
    </div>
    <div class="rv">
      <span class="eyebrow">{{ $service->title }}</span>
      <h2 class="h-lg">{{ $service->title }} <span class="or">في الرياض</span></h2>
      <p class="lead" style="margin-top:16px">{{ $service->body }}</p>
      @if($service->long_body)
        @foreach(preg_split("/\n{2,}/", $service->long_body) as $paragraph)
          <p class="lead" style="margin-top:14px">{{ $paragraph }}</p>
        @endforeach
      @endif
      @if($service->features)
        <ul class="vals">
          @foreach($service->features as $feature)<li><span class="ck">&#10003;</span>{{ $feature }}</li>@endforeach
        </ul>
      @endif
      <div class="btn-row" style="margin-top:28px">
        <a href="{{ route('contact') }}" class="btn btn-primary">اطلب عرض سعر <x-icon name="arrow" /></a>
        <a href="{{ whatsapp_url('استفسار عن: '.$service->title) }}" class="btn btn-ghost">استفسار واتساب</a>
      </div>
    </div>
  </div>
</section>

<section class="sec bg-cream">
  <div class="wrap">
    <x-sec-head center eyebrow="كيف نعمل" title="من أوّل مكالمة" or="حتى التشغيل" />
    <x-site.process :steps="$steps" />
  </div>
</section>

@if($others->isNotEmpty())
<section class="sec">
  <div class="wrap">
    <x-sec-head eyebrow="خدمات أخرى" title="قد يهمّك" or="أيضاً" />
    <div class="cards">
      @foreach($others as $other)<x-site.service-card :service="$other" />@endforeach
    </div>
  </div>
</section>
@endif

<x-site.cta-band />

</x-layouts.site>
