/* كنوز العالم للمصاعد — السكربت المشترك */
(function () {
  'use strict';
  var reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* --- البريلودر: يفتح بسرعة ولا يحبس الزائر ---
     لا ننتظر load (قد يتأخّر بسبب الصور/الخطوط): نفتح بمجرد جاهزية الـDOM
     بلمسة تأخير بسيطة، مع شبكة أمان قصيرة جداً. */
  function openDoors() { document.body.classList.add('ready'); }
  if (document.readyState !== 'loading') setTimeout(openDoors, 90);
  else document.addEventListener('DOMContentLoaded', function () { setTimeout(openDoors, 90); });
  setTimeout(openDoors, 1200);

  /* --- الهيدر عند التمرير + شريط تقدّم القراءة --- */
  var hdr = document.querySelector('.hdr');
  if (hdr) {
    var onScroll = function () {
      hdr.classList.toggle('stuck', scrollY > 40);
      var d = document.documentElement;
      var max = d.scrollHeight - innerHeight;
      hdr.style.setProperty('--sp', (max > 0 ? (scrollY / max) * 100 : 0).toFixed(2) + '%');
    };
    addEventListener('scroll', onScroll, { passive: true });
    addEventListener('resize', onScroll, { passive: true });
    onScroll();
  }

  /* --- الدرج الجانبي --- */
  var drawer = document.getElementById('drawer');
  if (drawer) {
    var open = function () { drawer.classList.add('open'); document.body.style.overflow = 'hidden'; };
    var shut = function () { drawer.classList.remove('open'); document.body.style.overflow = ''; };
    var burger = document.querySelector('.burger');
    if (burger) burger.onclick = open;
    drawer.querySelectorAll('[data-close]').forEach(function (el) { el.onclick = shut; });
    drawer.querySelectorAll('nav a').forEach(function (a) { a.onclick = shut; });
    addEventListener('keydown', function (e) { if (e.key === 'Escape') shut(); });
  }

  /* --- الظهور عند التمرير + عدّادات الأرقام --- */
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (!e.isIntersecting) return;
      e.target.classList.add('in');
      io.unobserve(e.target);
      e.target.querySelectorAll('[data-count]').forEach(function (el) {
        if (el.dataset.done) return;
        el.dataset.done = '1';
        var target = parseFloat(el.dataset.count);
        if (reduced) { el.textContent = target; return; }
        var t0 = performance.now(), dur = 1400;
        (function tick(t) {
          var p = Math.min((t - t0) / dur, 1);
          el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));
          if (p < 1) requestAnimationFrame(tick);
        })(t0);
      });
    });
  }, { threshold: .18 });
  document.querySelectorAll('.rv').forEach(function (el) { io.observe(el); });

  /* --- ظهور متتابع لعناصر الشبكات: كل بطاقة تتأخّر قليلاً عن سابقتها --- */
  if (!reduced) {
    var GRIDS = '.cards,.cards-2,.feat-grid,.sector-grid,.proj-grid,.gal-grid,'
      + '.art-grid,.quotes,.proc-steps,.stats-grid,.faq';
    document.querySelectorAll(GRIDS).forEach(function (grid) {
      var i = 0;
      Array.prototype.forEach.call(grid.children, function (child) {
        if (!child.classList.contains('rv')) { child.classList.add('rv'); io.observe(child); }
        // سقف 6 خطوات حتى لا تتأخّر الشبكات الطويلة أكثر من اللازم
        child.style.setProperty('--d', (Math.min(i, 6) * 0.09).toFixed(2) + 's');
        i++;
      });
    });
  }

  /* --- تأثير المغناطيس: البطاقة تميل وتنجذب نحو المؤشر ---
     نكتب transform مباشرة على العنصر (inline) لأنه يتفوق على أي :hover في الستايل شيت،
     مع perspective ليصبح الميلان مرئياً، وtransition سريع كي تتبع الحركة المؤشر. */
  var MAG = '.card,.feat,.proj,.quote,.pstep,.c-item,.a-card,.sector,.stats-grid .stat,.about-pic';
  var canHover = matchMedia('(hover:hover)').matches;
  if (!reduced && canHover) {
    document.querySelectorAll(MAG).forEach(function (el) {
      el.classList.add('magnet');
      var base = el.style.transition;
      el.addEventListener('pointerenter', function () {
        el.style.transition = 'transform .14s cubic-bezier(.22,1,.36,1)';
      });
      // بلا requestAnimationFrame: المتصفح يدمج أحداث pointermove في الإطار أصلاً
      el.addEventListener('pointermove', function (e) {
        var r = el.getBoundingClientRect();
        if (!r.width || !r.height) return;
        var px = (e.clientX - r.left) / r.width - .5;   // -0.5 .. 0.5
        var py = (e.clientY - r.top) / r.height - .5;
        el.style.transform = 'perspective(900px)'
          + ' translate3d(' + (px * 16).toFixed(1) + 'px,' + (py * 16 - 8).toFixed(1) + 'px,0)'
          + ' rotateX(' + (-py * 7).toFixed(2) + 'deg)'
          + ' rotateY(' + (px * 7).toFixed(2) + 'deg)'
          + ' scale(1.015)';
        el.style.setProperty('--gx', ((px + .5) * 100).toFixed(1) + '%');
        el.style.setProperty('--gy', ((py + .5) * 100).toFixed(1) + '%');
      });
      el.addEventListener('pointerleave', function () {
        el.style.transition = 'transform .45s cubic-bezier(.22,1,.36,1)';
        el.style.transform = '';
        setTimeout(function () { el.style.transition = base; }, 460);
      });
    });
  }

  /* --- اللايت بوكس للمعرض --- */
  var lb = document.getElementById('lightbox');
  if (lb) {
    var lbImg = lb.querySelector('img');
    document.querySelectorAll('[data-zoom]').forEach(function (el) {
      el.addEventListener('click', function (ev) {
        ev.preventDefault();
        var img = el.querySelector('img');
        if (!img) return;
        lbImg.src = img.src;
        lbImg.alt = img.alt || '';
        lb.classList.add('open');
        document.body.style.overflow = 'hidden';
      });
    });
    var closeLb = function () { lb.classList.remove('open'); document.body.style.overflow = ''; };
    lb.querySelector('.x').onclick = closeLb;
    lb.addEventListener('click', function (e) { if (e.target === lb) closeLb(); });
    addEventListener('keydown', function (e) { if (e.key === 'Escape') closeLb(); });
  }

  /* --- نموذج التواصل ---
     يُرسَل فعلياً للخادم (يُسجَّل في اللوحة). زر "إرسال عبر واتساب" الاختياري
     يفتح محادثة بالرقم الرسمي مسبوقة ببيانات النموذج — دون منع الحفظ. */
  var form = document.getElementById('quote-form');
  if (form) {
    var waNumber = form.getAttribute('data-wa') || '966503173407';
    var buildMsg = function () {
      var d = new FormData(form);
      return encodeURIComponent(
        'طلب عرض سعر — كنوز العالم للمصاعد\n'
        + 'الاسم: ' + (d.get('name') || '-') + '\n'
        + 'الجوال: ' + (d.get('phone') || '-') + '\n'
        + 'الخدمة: ' + (d.get('service') || '-') + '\n'
        + 'التفاصيل: ' + (d.get('details') || '-'));
    };
    var waBtn = document.getElementById('quote-wa');
    if (waBtn) {
      waBtn.addEventListener('click', function () {
        open('https://wa.me/' + waNumber + '?text=' + buildMsg(), '_blank');
      });
    }
  }
})();
