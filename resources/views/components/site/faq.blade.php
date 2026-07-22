@props(['faqs'])

<div class="faq rv">
  @foreach($faqs as $faq)
    <details><summary>{{ $faq->question }}</summary><p>{{ $faq->answer }}</p></details>
  @endforeach
</div>
