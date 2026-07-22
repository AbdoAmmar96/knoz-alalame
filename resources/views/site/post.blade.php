<x-layouts.site :seo="$seo">

<x-site.page-hero :title="$post->title" :eyebrow="$post->tag" :image="$post->image" :crumb="$post->title" />

<section class="sec">
  <div class="wrap">
    <article class="article rv">
      @if($post->excerpt)<p class="lead">{{ $post->excerpt }}</p>@endif
      @foreach(preg_split("/\n{2,}/", (string) $post->body) as $paragraph)
        @if(trim($paragraph) !== '')<p>{{ $paragraph }}</p>@endif
      @endforeach
      <div class="btn-row" style="margin-top:32px">
        <a href="{{ route('contact') }}" class="btn btn-primary">اطلب معاينة مجانية</a>
        <a href="{{ route('blog.index') }}" class="btn btn-ghost">كل المقالات</a>
      </div>
    </article>
  </div>
</section>

@if($related->isNotEmpty())
<section class="sec bg-cream">
  <div class="wrap">
    <x-sec-head eyebrow="اقرأ أيضاً" title="مقالات" or="ذات صلة" />
    <div class="art-grid">
      @foreach($related as $item)<x-site.post-card :post="$item" />@endforeach
    </div>
  </div>
</section>
@endif

<x-site.cta-band />

</x-layouts.site>
