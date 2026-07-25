<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>{{ $seo['title'] }}</title>
<meta name="description" content="{{ $seo['description'] }}" />
<meta name="robots" content="{{ $seo['robots'] }}" />
<link rel="canonical" href="{{ $seo['canonical'] }}" />
<meta name="theme-color" content="#EA711C" />
<meta property="og:type" content="website" />
<meta property="og:locale" content="ar_SA" />
<meta property="og:site_name" content="{{ setting('site_name') }}" />
<meta property="og:title" content="{{ $seo['title'] }}" />
<meta property="og:description" content="{{ $seo['description'] }}" />
<meta property="og:url" content="{{ $seo['canonical'] }}" />
<meta property="og:image" content="{{ $seo['image'] }}" />
<meta name="twitter:card" content="summary_large_image" />
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />
@foreach($seo['jsonld'] as $schema)
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endforeach
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('assets/style.css') }}?v={{ filemtime(public_path('assets/style.css')) }}" />
</head>
<body>

<div id="pre" aria-hidden="true">
  <div class="door l"></div>
  <div class="door r"></div>
  <div class="hud">
    <div class="plate"><img src="{{ asset('logo-white.png') }}" alt="{{ setting('site_name') }}" /></div>
  </div>
</div>

<a href="#main" class="skip">تخطَّ إلى المحتوى</a>

<header class="hdr">
  <div class="wrap hdr-in">
    <a href="{{ route('home') }}" class="brand" aria-label="{{ setting('site_name') }}"><img src="{{ asset('logo.png') }}" alt="{{ setting('site_name') }}" /></a>
    <nav class="nav">
      <ul>
        @foreach(nav_links() as $link)
          @if($link['key'] === 'services' && $footerServices->isNotEmpty())
            <li class="has-sub">
              <a href="{{ $link['url'] }}" @class(['active' => $link['active']])>
                {{ $link['label'] }}
                <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
              </a>
              <ul class="subnav">
                @foreach($footerServices as $service)
                  <li><a href="{{ route('services.show', $service) }}">{{ $service->title }}</a></li>
                @endforeach
                <li class="subnav-all"><a href="{{ route('services') }}">كل الخدمات</a></li>
              </ul>
            </li>
          @else
            <li><a href="{{ $link['url'] }}" @class(['active' => $link['active']])>{{ $link['label'] }}</a></li>
          @endif
        @endforeach
      </ul>
    </nav>
    <div class="hdr-cta">
      <a href="{{ whatsapp_url() }}" class="hdr-phone"><x-icon name="whatsapp" /><span>{{ setting('phone_display') }}</span></a>
      <a href="{{ route('contact') }}" class="btn btn-primary">اطلب عرض سعر</a>
      <button class="burger" aria-label="فتح القائمة"><span></span><span></span><span></span></button>
    </div>
  </div>
</header>

<div class="drawer" id="drawer">
  <div class="drawer-bg" data-close></div>
  <div class="drawer-panel">
    <div class="drawer-top">
      <img src="{{ asset('logo.png') }}" alt="{{ setting('site_name') }}" style="height:42px" />
      <button class="drawer-close" data-close aria-label="إغلاق">&#10005;</button>
    </div>
    <nav>
      @foreach(nav_links() as $i => $link)
        <a href="{{ $link['url'] }}" @class(['active' => $link['active']])>{{ $link['label'] }} <span>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span></a>
        @if($link['key'] === 'services' && $footerServices->isNotEmpty())
          <div class="drawer-sub">
            @foreach($footerServices as $service)
              <a href="{{ route('services.show', $service) }}">{{ $service->title }}</a>
            @endforeach
          </div>
        @endif
      @endforeach
    </nav>
    <div class="drawer-foot">
      <a href="tel:{{ setting('phone') }}" class="btn btn-primary">اتصال مباشر</a>
      <a href="{{ whatsapp_url() }}" class="btn btn-ghost">تواصل واتساب</a>
    </div>
  </div>
</div>

<main id="main">
{{ $slot }}
</main>

<footer class="ftr">
  <div class="wrap">
    <div class="ftr-grid">
      <div>
        <div class="brand-mark"><img src="{{ asset('logo-white.png') }}" alt="{{ setting('site_name') }}" /></div>
        <p>{{ setting('footer_about') }}</p>
      </div>
      <div>
        <h4>روابط سريعة</h4>
        <ul>@foreach(nav_links() as $link)<li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>@endforeach</ul>
      </div>
      <div>
        <h4>خدماتنا</h4>
        <ul>
          @foreach($footerServices as $service)
            <li><a href="{{ route('services.show', $service) }}">{{ $service->title }}</a></li>
          @endforeach
          <li><a href="{{ route('contracts') }}">عقود الصيانة السنوية</a></li>
        </ul>
      </div>
      <div>
        <h4>تواصل معنا</h4>
        <ul class="ftr-contact">
          <li><x-icon name="phone" /><a href="tel:{{ setting('phone') }}" style="direction:ltr">{{ setting('phone') }}</a></li>
          <li><x-icon name="pin" /><span>{{ setting('address') }}</span></li>
          <li><x-icon name="clock" /><span>{{ setting('hours_note') }}</span></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="ftr-bottom"><div class="wrap">{!! setting('copyright') !!}</div></div>
</footer>

<x-site.social-fab />

<script src="{{ asset('assets/app.js') }}?v={{ filemtime(public_path('assets/app.js')) }}"></script>
</body>
</html>
