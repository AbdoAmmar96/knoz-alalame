@props(['points', 'image' => null])

<section class="sec contracts-band">
  @if($image)<img class="band-bg" src="{{ img($image) }}" alt="" loading="lazy" />@endif
  <div class="wrap"><div class="about-grid">
    <div class="rv">
      <span class="eyebrow">عقود الصيانة السنوية</span>
      <h2 class="h-lg">عطل المصعد يكلّف أكثر <span class="or">من عقد صيانته</span></h2>
      <p class="lead" style="color:var(--tx-d2);margin:16px 0 28px">يشمل عقد الصيانة السنوي زيارات دورية مجدولة، وأولوية في الطوارئ، وخصماً على قطع الغيار. تعرّف على الباقة المناسبة لمبناك.</p>
      <a href="{{ route('contracts') }}" class="btn btn-primary">اطلب عرض عقد صيانة</a>
    </div>
    <ul class="rv check-list">
      @foreach($points as $point)<li><x-icon name="check" />{{ $point->title }}</li>@endforeach
    </ul>
  </div></div>
</section>
