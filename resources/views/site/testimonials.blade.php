<x-layouts.site :seo="$seo">

<x-site.page-hero title="آراء العملاء" eyebrow="قالوا عنا" :image="setting('hero_image_about')"
  lead="ثقة عملائنا هي أهم ما نبنيه. هذه بعض تجارب من تعاملوا مع كنوز العالم في تركيب المصاعد وصيانتها وتحديثها في الرياض."
  :tags="['التزام بالمواعيد', 'خدمة موثوقة', 'رضا العملاء']" />

<section class="sec">
  <div class="wrap">
    <x-sec-head center eyebrow="آراء العملاء" title="قالوا" or="عنا">
      نماذج حقيقية من آراء عملائنا في القطاعات السكنية والتجارية والصناعية.
    </x-sec-head>
    <div class="quotes">
      @foreach($testimonials as $quote)<x-site.testimonial :quote="$quote" />@endforeach
    </div>
  </div>
</section>

@if($clients->isNotEmpty())
<section class="sec bg-cream">
  <div class="wrap">
    <x-sec-head center eyebrow="عملاؤنا" title="عملاء نفخر" or="بخدمتهم">
      مشاريع تركيب حقيقية نفّذناها في الرياض لعملاء من الأفراد والشركات والجهات — سكني وتجاري وطبي.
    </x-sec-head>
    <x-site.clients :clients="$clients" />
  </div>
</section>
@endif

<x-site.stats-band :stats="$stats" :image="setting('hero_image_gallery')" />

<x-site.cta-band />

</x-layouts.site>
