<x-layouts.site :seo="$seo">

<x-site.page-hero title="أعمالنا" eyebrow="المشاريع" :image="setting('hero_image_gallery')"
  lead="نماذج من المصاعد التي رّكبناها وحدّثناها في الرياض: أبراج سكنية، فنادق ومراكز تجارية، مصانع ومستودعات، وفلل خاصة — أعمال حقيقية تتحدّث عن جودتنا."
  :tags="['سكني', 'تجاري', 'صناعي']" />

<section class="sec">
  <div class="wrap">
    <x-sec-head eyebrow="المشاريع" title="أعمالنا" or="تتكلم">
      نماذج من المصاعد التي رّكبناها وحدّثناها في الرياض.
    </x-sec-head>
    <div class="gal-grid">
      @foreach($projects as $project)<x-site.project-card :project="$project" zoom />@endforeach
    </div>
  </div>
</section>

<x-site.marquee />

<x-site.stats-band :stats="$stats" :image="setting('hero_image_about')" />

<x-site.cta-band />

<div class="lightbox" id="lightbox">
  <button class="x" aria-label="إغلاق">&#10005;</button>
  <img src="" alt="" />
</div>

</x-layouts.site>
