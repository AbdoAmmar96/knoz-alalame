@props(['eyebrow' => 'ابدأ الآن', 'title' => 'هل أنت مستعد للبدء؟ المعاينة مجانية'])

<section class="sec cta-band">
  <div class="lines"></div><div class="sheen"></div>
  <div class="wrap"><div class="rv">
    <span class="eyebrow">{{ $eyebrow }}</span>
    <h2 class="h-lg">{{ $title }}</h2>
    <p>{{ trim($slot) !== '' ? $slot : 'اتصل بنا الآن أو اترك بياناتك، ويتواصل معك مهندس خلال ساعات العمل.' }}</p>
    <div class="btn-row">
      <a href="tel:{{ setting('phone') }}" class="btn btn-light"><x-icon name="phone" /><span style="direction:ltr">{{ phone_pretty() }}</span></a>
      <a href="{{ whatsapp_url() }}" class="btn btn-outline-d">تواصل واتساب</a>
    </div>
  </div></div>
</section>
