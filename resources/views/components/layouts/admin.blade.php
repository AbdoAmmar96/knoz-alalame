@props(['title' => 'لوحة التحكم', 'crumb' => null])

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="robots" content="noindex,nofollow" />
<title>{{ $title }} — لوحة تحكم كنوز العالم</title>
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('assets/admin.css') }}?v={{ filemtime(public_path('assets/admin.css')) }}" />
</head>
<body>

<div class="side-bg" data-side-close></div>

<div class="shell">
  <aside class="side" id="side">
    <a href="{{ route('admin.dashboard') }}" class="brand"><img src="{{ asset('logo-white.png') }}" alt="كنوز العالم" /></a>

    <a href="{{ route('admin.dashboard') }}" @class(['on' => request()->routeIs('admin.dashboard')])>
      <x-admin-icon name="home" /> لوحة البيانات
    </a>
    <a href="{{ route('admin.leads.index') }}" @class(['on' => request()->routeIs('admin.leads.*')])>
      <x-admin-icon name="inbox" /> طلبات العملاء
      @if($newLeadsCount = \App\Models\Lead::where('status', 'new')->count())
        <span class="badge">{{ $newLeadsCount }}</span>
      @endif
    </a>

    <div class="sec-lbl">محتوى الموقع</div>
    @foreach(\App\Admin\Resource::all() as $key => $def)
      <a href="{{ route('admin.resource.index', $key) }}"
         @class(['on' => request()->is('admin/'.$key.'*')])>
        <x-admin-icon :name="$def['icon']" /> {{ $def['label'] }}
      </a>
    @endforeach

    <div class="sec-lbl">الإعدادات</div>
    <a href="{{ route('admin.settings.edit') }}" @class(['on' => request()->routeIs('admin.settings.*')])>
      <x-admin-icon name="cog" /> إعدادات الموقع
    </a>
    <a href="{{ route('admin.account.edit') }}" @class(['on' => request()->routeIs('admin.account.*')])>
      <x-admin-icon name="user" /> حسابي
      @if(auth()->user()?->email === 'admin@konozcompany.com')<span class="badge">!</span>@endif
    </a>
    <a href="{{ route('home') }}" target="_blank"><x-admin-icon name="external" /> عرض الموقع</a>

    <div class="foot">
      <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="btn btn-ghost btn-block btn-sm"><x-admin-icon name="logout" /> تسجيل الخروج</button>
      </form>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div style="display:flex;align-items:center;gap:12px">
        <button class="burger-admin" data-side-open aria-label="القائمة"><x-admin-icon name="menu" /></button>
        <div>
          <h1>{{ $title }}</h1>
          @if($crumb)<div class="crumb">{{ $crumb }}</div>@endif
        </div>
      </div>
      @isset($actions){{ $actions }}@endisset
    </div>

    @if(session('ok'))
      <div class="flash"><x-admin-icon name="check" /> {{ session('ok') }}</div>
    @endif
    @if($errors->any())
      <div class="flash err"><x-admin-icon name="alert" /> راجع الحقول المعلَّمة بالأحمر ({{ $errors->count() }}).</div>
    @endif

    {{ $slot }}
  </div>
</div>

<script>
(function () {
  'use strict';
  var side = document.getElementById('side');
  var bg = document.querySelector('.side-bg');
  var open = function () { side.classList.add('open'); bg.classList.add('on'); };
  var shut = function () { side.classList.remove('open'); bg.classList.remove('on'); };
  document.querySelectorAll('[data-side-open]').forEach(function (b) { b.onclick = open; });
  document.querySelectorAll('[data-side-close]').forEach(function (b) { b.onclick = shut; });

  /* تأكيد قبل أي حذف */
  document.querySelectorAll('form[data-confirm]').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      if (!confirm(f.dataset.confirm)) e.preventDefault();
    });
  });

  /* معاينة الصورة فور اختيارها */
  document.querySelectorAll('input[type=file][data-preview]').forEach(function (input) {
    input.addEventListener('change', function () {
      var box = document.querySelector(input.dataset.preview);
      if (!box || !input.files[0]) return;
      box.innerHTML = '<img alt="" src="' + URL.createObjectURL(input.files[0]) + '">';
    });
  });

  /* ترتيب الصفوف بالسحب والإفلات */
  var tbody = document.querySelector('tbody[data-sortable]');
  if (tbody) {
    var dragged = null;
    tbody.querySelectorAll('tr').forEach(function (tr) {
      tr.draggable = true;
      tr.addEventListener('dragstart', function () { dragged = tr; tr.classList.add('dragging'); });
      tr.addEventListener('dragend', function () {
        tr.classList.remove('dragging');
        document.getElementById('reorder-bar').hidden = false;
        // إعادة بناء الحقول المخفية بالترتيب الجديد
        var form = document.getElementById('reorder-form');
        form.querySelectorAll('input[name^="order"]').forEach(function (i) { i.remove(); });
        tbody.querySelectorAll('tr').forEach(function (row) {
          var input = document.createElement('input');
          input.type = 'hidden'; input.name = 'order[]'; input.value = row.dataset.id;
          form.appendChild(input);
        });
      });
      tr.addEventListener('dragover', function (e) {
        e.preventDefault();
        if (!dragged || dragged === tr) return;
        var box = tr.getBoundingClientRect();
        var after = (e.clientY - box.top) > box.height / 2;
        tbody.insertBefore(dragged, after ? tr.nextSibling : tr);
      });
    });
  }
})();
</script>
</body>
</html>
