<x-layouts.site :seo="$seo">

<x-site.page-hero title="المقالات" eyebrow="المدوّنة" :image="setting('hero_image_blog')"
  lead="مقالات تشرح صيانة المصاعد وتركيبها وأنواعها وأسعارها في الرياض، ونصائح عملية لأصحاب المباني تساعدك على اتخاذ القرار الصحيح لمصعدك."
  :tags="['صيانة', 'تركيب', 'تحديث']" />

<section class="sec">
  <div class="wrap">
    <x-sec-head eyebrow="المدوّنة" title="اعرف مصعدك" or="أكثر">
      مقالات تشرح صيانة المصاعد وتركيبها وأنواعها، ونصائح عملية لأصحاب المباني في الرياض.
    </x-sec-head>
    <div class="art-grid">
      @foreach($posts as $post)<x-site.post-card :post="$post" />@endforeach
    </div>
    @if($posts->hasPages())
      <div class="proj-more rv">{{ $posts->links() }}</div>
    @endif
  </div>
</section>

<x-site.marquee />

<x-site.stats-band :image="setting('hero_image_about')" />

<x-site.cta-band />

</x-layouts.site>
