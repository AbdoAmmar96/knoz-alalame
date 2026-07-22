<x-layouts.site :seo="$seo">

<x-site.page-hero title="نبذة عنا" eyebrow="نبذة عنا" :image="setting('hero_image_about')" />

<section class="sec">
  <div class="wrap about-grid">
    <div class="about-pic rv">
      <img src="{{ img(setting('about_image')) }}" alt="{{ setting('site_name') }}" loading="lazy" />
      <div class="badge"><b>{{ $yearsStat?->value ?? '15' }}+</b><small>عاماً في سوق المصاعد</small></div>
    </div>
    <div class="rv">
      <span class="eyebrow">قصتنا</span>
      <h2 class="h-lg">تخصص واحد منذ اليوم الأول: <span class="or">المصاعد</span></h2>
      @foreach($aboutParagraphs as $paragraph)
        <p class="lead" style="margin-top:16px">{{ $paragraph }}</p>
      @endforeach
      <ul class="vals">
        @foreach($aboutValues as $value)<li><span class="ck">&#10003;</span>{{ $value }}</li>@endforeach
      </ul>
    </div>
  </div>
</section>

<x-site.marquee />

<x-site.stats-band :stats="$stats" :image="setting('hero_image_gallery')" />

<section class="sec bg-cream">
  <div class="wrap">
    <x-sec-head center eyebrow="لماذا كنوز العالم؟" title="وعود مكتوبة.." or="لا كلام مرسل" />
    <div class="feat-grid">
      @foreach($features as $feature)<x-site.feature-card :feature="$feature" />@endforeach
    </div>
  </div>
</section>

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
    <x-sec-head center eyebrow="آراء العملاء" title="قالوا" or="عنا" />
    <div class="quotes">
      @foreach($testimonials as $quote)<x-site.testimonial :quote="$quote" />@endforeach
    </div>
  </div>
</section>

<x-site.cta-band />

</x-layouts.site>
