<x-layouts.site :seo="$seo">

<x-site.hero :stats="$stats" />

<x-site.marquee :items="$marquee" />

<section class="sec bg-soft" id="services">
  <div class="wrap">
    <x-sec-head eyebrow="خدماتنا" title="ثلاث خدمات.." or="تخصص واحد">
      كل ما نقوم به يدور حول المصاعد: نُركّبها وفق الأصول منذ البداية، ونصونها لتبقى آمنة، ونُحدّث القديم منها بدلاً من استبداله.
    </x-sec-head>
    <div class="cards">
      @foreach($services as $service)<x-site.service-card :service="$service" />@endforeach
    </div>
  </div>
</section>

<x-site.stats-band :stats="$stats" :image="setting('hero_image_gallery')" />

<section class="sec">
  <div class="wrap">
    <x-sec-head eyebrow="القطاعات التي نخدمها" title="سكني.. تجاري.." or="صناعي" />
    <div class="sector-grid">
      @foreach($sectors as $sector)<x-site.sector-card :sector="$sector" />@endforeach
    </div>
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

<section class="sec">
  <div class="wrap">
    <x-sec-head center eyebrow="كيف نعمل" title="من أوّل مكالمة" or="حتى التشغيل">
      خطوات واضحة ومحسوبة، تعرف في كل مرحلة أين أنت وما الذي يأتي بعدها.
    </x-sec-head>
    <x-site.process :steps="$steps" />
  </div>
</section>

<section class="sec bg-cream">
  <div class="wrap">
    <x-sec-head eyebrow="المشاريع" title="أعمالنا" or="تتكلم">
      نماذج من المصاعد التي رّكبناها وحدّثناها في الرياض.
    </x-sec-head>
    <div class="proj-grid">
      @foreach($projects as $project)<x-site.project-card :project="$project" />@endforeach
    </div>
    <div class="proj-more rv"><a href="{{ route('gallery') }}" class="btn btn-ghost">تصفّح جميع الأعمال</a></div>
  </div>
</section>

<x-site.contracts-band :points="$contractPoints" :image="setting('hero_image_about')" />

<section class="sec bg-cream2">
  <div class="wrap">
    <x-sec-head center eyebrow="آراء العملاء" title="قالوا" or="عنا" />
    <div class="quotes">
      @foreach($testimonials as $quote)<x-site.testimonial :quote="$quote" />@endforeach
    </div>
  </div>
</section>

<x-site.cta-band />

</x-layouts.site>
