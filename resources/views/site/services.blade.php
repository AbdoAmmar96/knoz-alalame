<x-layouts.site :seo="$seo">

<x-site.page-hero title="خدماتنا" eyebrow="خدماتنا" :image="setting('hero_image_services')" />

<section class="sec">
  <div class="wrap">
    <x-sec-head eyebrow="ما نقدّمه" title="كل ما يحتاجه مبناك" or="من مصاعد">
      كل ما نقوم به يدور حول المصاعد: نُركّبها وفق الأصول منذ البداية، ونصونها لتبقى آمنة، ونُحدّث القديم منها بدلاً من استبداله.
    </x-sec-head>
    <div class="cards">
      @foreach($services as $service)<x-site.service-card :service="$service" />@endforeach
    </div>
  </div>
</section>

<x-site.marquee />

<section class="sec bg-cream">
  <div class="wrap">
    <x-sec-head eyebrow="القطاعات التي نخدمها" title="سكني.. تجاري.." or="صناعي" />
    <div class="sector-grid">
      @foreach($sectors as $sector)<x-site.sector-card :sector="$sector" />@endforeach
    </div>
  </div>
</section>

<x-site.stats-band :image="setting('hero_image_gallery')" />

<section class="sec">
  <div class="wrap">
    <x-sec-head center eyebrow="كيف نعمل" title="من أوّل مكالمة" or="حتى التشغيل">
      خطوات واضحة ومحسوبة، تعرف في كل مرحلة أين أنت وما الذي يأتي بعدها.
    </x-sec-head>
    <x-site.process :steps="$steps" />
  </div>
</section>

<section class="sec bg-cream2">
  <div class="wrap">
    <x-sec-head center eyebrow="لماذا كنوز العالم؟" title="وعود مكتوبة.." or="لا كلام مرسل" />
    <div class="feat-grid">
      @foreach($features as $feature)<x-site.feature-card :feature="$feature" />@endforeach
    </div>
  </div>
</section>

<x-site.contracts-band :points="$contractPoints" :image="setting('hero_image_about')" />

<x-site.cta-band />

</x-layouts.site>
