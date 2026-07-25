<x-layouts.site :seo="$seo">

<x-site.page-hero :title="$project->title" eyebrow="من أعمالنا" :crumb="$project->title"
  lead="مشروع منفّذ من كنوز العالم للمصاعد في الرياض — الصورة والتفاصيل الكاملة في الأسفل."
  :tags="$project->tag ? [$project->tag] : []" />

<section class="sec">
  <div class="wrap">
    <div class="proj-detail">
      <figure class="proj-figure rv">
        <img src="{{ img($project->image) }}" alt="{{ $project->title }}" />
      </figure>
      <div class="proj-copy rv">
        @if($project->tag)<span class="chip">{{ $project->tag }}</span>@endif
        <h2>عن المشروع</h2>
        <p>{{ $project->body ?: $project->title.' — من أعمال كنوز العالم للمصاعد في الرياض.' }}</p>
        <ul class="proj-meta">
          <li><span>النوع</span><b>{{ $project->tag ?? 'مصعد' }}</b></li>
          <li><span>الموقع</span><b>الرياض</b></li>
          <li><span>التنفيذ</span><b>كنوز العالم للمصاعد</b></li>
        </ul>
        <div class="btn-row">
          <a href="{{ route('contact') }}" class="btn btn-primary">اطلب مشروعاً مماثلاً <x-icon name="arrow" /></a>
          <a href="{{ route('gallery') }}" class="btn btn-ghost">كل الأعمال</a>
        </div>
      </div>
    </div>
  </div>
</section>

@if($others->isNotEmpty())
<section class="sec bg-cream">
  <div class="wrap">
    <x-sec-head eyebrow="أعمال أخرى" title="مشاريع" or="ذات صلة" />
    <div class="gal-grid">
      @foreach($others as $p)<x-site.project-card :project="$p" />@endforeach
    </div>
  </div>
</section>
@endif

<x-site.cta-band />

</x-layouts.site>
