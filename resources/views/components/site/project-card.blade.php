@props(['project'])

<a href="{{ route('gallery.show', $project) }}" class="proj rv">
  <div class="art"><img src="{{ img($project->image) }}" alt="{{ $project->title }}" loading="lazy" /></div>
  <div class="body"><b>{{ $project->title }}</b>@if($project->tag)<span class="tag">{{ $project->tag }}</span>@endif</div>
</a>
