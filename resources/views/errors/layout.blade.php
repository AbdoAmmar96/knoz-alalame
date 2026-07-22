{{-- صفحات الأخطاء مستقلّة تماماً: لا تقرأ الإعدادات من قاعدة البيانات
     لأن الخطأ نفسه قد يكون في الاتصال بها. --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="robots" content="noindex,follow" />
<title>@yield('title') — كنوز العالم للمصاعد</title>
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet" />
<style>
  :root{--orange:#EA711C;--orange-2:#F18335;--orange-3:#C25A0F;--ink:#14181E;--ink-2:#1D2229;
    --cream:#FBF7F2;--cream-2:#F5EFE6;--tx:#1B2027;--tx-2:#5C6672;--line:#EAE3D9}
  *{margin:0;padding:0;box-sizing:border-box}
  body{font-family:'Cairo',system-ui,sans-serif;color:var(--tx-2);line-height:1.9;
    min-height:100svh;display:grid;place-items:center;padding:28px;overflow:hidden;
    background:linear-gradient(140deg,#FFFDFB 0%,var(--cream) 38%,var(--cream-2) 72%,#FDEEDF 100%);
    background-size:220% 220%;animation:pan 26s ease-in-out infinite alternate}
  @keyframes pan{from{background-position:0% 0%}to{background-position:100% 100%}}
  .glow{position:fixed;border-radius:50%;filter:blur(70px);pointer-events:none;z-index:0}
  .glow.a{width:min(460px,52vw);aspect-ratio:1;top:-8%;inset-inline-end:4%;
    background:radial-gradient(circle,rgba(233,113,28,.42),transparent 68%);animation:f1 17s ease-in-out infinite alternate}
  .glow.b{width:min(380px,46vw);aspect-ratio:1;bottom:-10%;inset-inline-start:2%;
    background:radial-gradient(circle,rgba(255,183,105,.4),transparent 68%);animation:f2 21s ease-in-out infinite alternate}
  @keyframes f1{to{transform:translate3d(-7%,9%,0) scale(1.16)}}
  @keyframes f2{to{transform:translate3d(9%,-8%,0) scale(.92)}}
  .box{position:relative;z-index:1;text-align:center;max-width:560px}
  .box img{height:74px;width:auto;margin:0 auto 30px}
  .code{font-size:clamp(4.6rem,17vw,8.5rem);font-weight:800;line-height:1;letter-spacing:-.03em;
    background-image:linear-gradient(100deg,var(--orange-3),var(--orange) 30%,#FFB864 52%,var(--orange) 74%,var(--orange-3));
    background-size:250% 100%;-webkit-background-clip:text;background-clip:text;
    color:transparent;-webkit-text-fill-color:transparent;animation:shine 7s linear infinite;direction:ltr}
  @keyframes shine{from{background-position:0% 0}to{background-position:100% 0}}
  h1{font-size:clamp(1.35rem,4vw,1.9rem);color:var(--tx);font-weight:800;margin:6px 0 12px}
  p{font-size:1.02rem;margin-bottom:32px}
  .row{display:flex;gap:13px;justify-content:center;flex-wrap:wrap}
  .btn{display:inline-flex;align-items:center;gap:9px;font-weight:700;font-size:.96rem;
    padding:14px 28px;border-radius:999px;border:2px solid transparent;text-decoration:none;
    transition:transform .25s cubic-bezier(.22,1,.36,1),box-shadow .25s,background .25s,color .25s,border-color .25s}
  .btn svg{width:18px;height:18px}
  .p{background:linear-gradient(115deg,var(--orange-2),var(--orange) 45%,var(--orange-3));color:#fff}
  .p:hover{transform:translateY(-3px);box-shadow:0 14px 34px rgba(233,113,28,.34)}
  .g{border-color:#DED5C8;color:var(--tx);background:rgba(255,255,255,.7)}
  .g:hover{border-color:var(--orange);color:var(--orange-3);transform:translateY(-3px)}
  @media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
</style>
</head>
<body>
<span class="glow a"></span><span class="glow b"></span>

<div class="box">
  <a href="{{ url('/') }}"><img src="{{ asset('logo.png') }}" alt="كنوز العالم للمصاعد" /></a>
  <div class="code">@yield('code')</div>
  <h1>@yield('title')</h1>
  <p>@yield('message')</p>
  <div class="row">
    <a href="{{ url('/') }}" class="btn p">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      العودة للرئيسية
    </a>
    @hasSection('extra')@yield('extra')@endif
  </div>
</div>

</body>
</html>
