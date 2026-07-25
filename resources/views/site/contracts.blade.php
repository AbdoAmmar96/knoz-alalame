<x-layouts.site :seo="$seo">

<x-site.page-hero title="عقود الصيانة السنوية" eyebrow="عقود الصيانة" :image="setting('hero_image_services')"
  lead="عقد الصيانة السنوي يبقي مصعدك يعمل بأمان طوال العام: زيارات دورية مجدولة، أولوية في الطوارئ، خصم على قطع الغيار، وفحص سلامة سنوي موثّق."
  :tags="['زيارات دورية', 'أولوية طوارئ', 'خصم قطع الغيار']" />

<section class="sec">
  <div class="wrap about-grid">
    <div class="rv">
      <span class="eyebrow">لماذا عقد سنوي؟</span>
      <h2 class="h-lg">عطل المصعد يكلّف أكثر <span class="or">من عقد صيانته</span></h2>
      <p class="lead" style="margin-top:16px">يشمل عقد الصيانة السنوي زيارات دورية مجدولة، وأولوية في الطوارئ، وخصماً على قطع الغيار، وفحص سلامة سنوياً موثّقاً. تعرّف على الباقة المناسبة لمبناك.</p>
      <div class="btn-row" style="margin-top:28px">
        <a href="{{ route('contact') }}" class="btn btn-primary">اطلب عرض عقد صيانة <x-icon name="arrow" /></a>
        <a href="{{ whatsapp_url('استفسار عن عقد صيانة سنوي') }}" class="btn btn-ghost">استفسار واتساب</a>
      </div>
    </div>
    <div class="rv">
      <div class="feat-grid" style="grid-template-columns:1fr">
        @foreach($contractPoints as $point)
          <article class="feat"><div class="ic"><x-icon name="check" /></div><h3>{{ $point->title }}</h3></article>
        @endforeach
      </div>
    </div>
  </div>
</section>

<x-site.stats-band :stats="$stats" :image="setting('hero_image_gallery')" />

<section class="sec bg-cream">
  <div class="wrap">
    <x-sec-head center eyebrow="أسئلة شائعة" title="أسئلة" or="تتكرر علينا" />
    <x-site.faq :faqs="$faqs" />
  </div>
</section>

<x-site.cta-band />

</x-layouts.site>
