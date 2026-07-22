@props(['steps'])

<div class="process">
  <div class="proc-line"></div>
  <div class="proc-steps">
    @foreach($steps as $i => $step)
      <article class="pstep rv">
        <div class="node">
          {!! $step->icon !!}
          <span class="n">{{ $i + 1 }}</span>
        </div>
        <h3>{{ $step->title }}</h3><p>{{ $step->body }}</p>
      </article>
    @endforeach
  </div>
</div>
