@props(['items' => null])

@php $items ??= \App\Models\MarqueeItem::live(); @endphp

{{-- أربع نسخ من المحتوى: الحركة تزيح نسخة واحدة (25%) فتبقى ثلاث نسخ مغطّية الشاشة دائماً. --}}
<div class="marquee"><div class="marquee-track">
  @for($copy = 0; $copy < 4; $copy++)
    @foreach($items as $item)
      <span class="item" @if($copy > 0) aria-hidden="true" @endif>{{ $item->title }}</span>
    @endforeach
  @endfor
</div></div>
