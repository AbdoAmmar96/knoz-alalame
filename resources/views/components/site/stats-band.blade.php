@props(['stats' => null, 'image' => null])

@php $stats ??= \App\Models\Stat::live(); @endphp

<section class="sec-tight stats-band">
  @if($image)<div class="stats-bg" style="background-image:url('{{ img($image) }}')"></div>@endif
  <div class="wrap"><div class="stats-grid rv">
    @foreach($stats as $stat)
      <div class="stat">
        <span class="num">@if($stat->is_counter)<span data-count="{{ $stat->value }}">0</span>{{ $stat->suffix }}@else{{ $stat->value }}@endif</span>
        <span class="lbl">{{ $stat->label }}</span>
      </div>
    @endforeach
  </div></div>
</section>
