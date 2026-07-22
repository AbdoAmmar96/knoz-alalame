@props(['eyebrow' => null, 'title' => null, 'or' => null, 'center' => false])

<div @class(['sec-head', 'center' => $center, 'rv'])>
  @if($eyebrow)<span class="eyebrow">{{ $eyebrow }}</span>@endif
  @if($title)<h2 class="h-lg">{{ $title }} @if($or)<span class="or">{{ $or }}</span>@endif</h2>@endif
  @if(trim($slot) !== '')<p>{{ $slot }}</p>@endif
</div>
