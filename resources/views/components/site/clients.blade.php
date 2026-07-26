@props(['clients'])

@php
  $icons = [
    'villa' => '<path d="m3 10 9-7 9 7v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/>',
    'building' => '<rect x="4" y="2" width="16" height="20" rx="1"/><path d="M9 22v-4h6v4M8 6h.01M12 6h.01M16 6h.01M8 10h.01M12 10h.01M16 10h.01M8 14h.01M12 14h.01M16 14h.01"/>',
    'tower' => '<path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18"/><path d="M2 22h20M10 6h4M10 10h4M10 14h4M10 18h4"/>',
    'medical' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v8M8 12h8"/>',
  ];
@endphp

<div class="clients-grid">
  @foreach($clients as $c)
    <article class="client-card rv">
      <div class="client-top">
        <span class="client-ic">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">{!! $icons[$c['type']] ?? $icons['building'] !!}</svg>
        </span>
        <div class="client-id">
          <b>{{ $c['name'] }}</b>
          <span class="client-area"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>{{ $c['area'] }}</span>
        </div>
        <span class="client-check" title="تم التركيب">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        </span>
      </div>
      @if(!empty($c['note']))
        <p class="client-note">{{ $c['note'] }}</p>
      @endif
      <span class="client-work">{{ $c['work'] }}</span>
    </article>
  @endforeach
</div>
