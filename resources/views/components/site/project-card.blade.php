@props(['project', 'zoom' => false])

<a @if($zoom) href="#" data-zoom @else href="{{ route('gallery') }}" @endif class="proj rv">
  <div class="art"><img src="{{ img($project->image) }}" alt="{{ $project->title }}" loading="lazy" /></div>
  <div class="body"><b>{{ $project->title }}</b>@if($project->tag)<span class="tag">{{ $project->tag }}</span>@endif</div>
</a>
