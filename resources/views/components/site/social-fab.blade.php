@php
    // كل منصّة تظهر فقط إن كان رابطها مضبوطاً. واتساب يُبنى من الرقم.
    // إنستغرام لا يظهر هنا بجانب الموقع — مكانه في الفوتر فقط.
    $socials = array_values(array_filter([
        ['key' => 'whatsapp', 'label' => 'واتساب', 'icon' => 'whatsapp', 'url' => setting('whatsapp') ? whatsapp_url() : null],
        ['key' => 'tiktok', 'label' => 'تيك توك', 'icon' => 'tiktok', 'url' => setting('social_tiktok')],
        ['key' => 'snapchat', 'label' => 'سناب شات', 'icon' => 'snapchat', 'url' => setting('social_snapchat')],
        ['key' => 'facebook', 'label' => 'فيسبوك', 'icon' => 'facebook', 'url' => setting('social_facebook')],
        ['key' => 'x', 'label' => 'إكس', 'icon' => 'x', 'url' => setting('social_x')],
        ['key' => 'youtube', 'label' => 'يوتيوب', 'icon' => 'youtube', 'url' => setting('social_youtube')],
    ], fn ($s) => ! empty($s['url'])));
@endphp

@if($socials)
<div class="social-fab" aria-label="تواصل اجتماعي">
  @foreach($socials as $s)
    <a href="{{ $s['url'] }}" class="sf sf-{{ $s['key'] }}" target="_blank" rel="noopener"
       aria-label="{{ $s['label'] }}">
      <x-icon :name="$s['icon']" />
      <span>{{ $s['label'] }}</span>
    </a>
  @endforeach
</div>
@endif
