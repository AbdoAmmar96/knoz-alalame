<x-layouts.site :seo="$seo">

<x-site.page-hero title="تواصل معنا" eyebrow="تواصل" :image="setting('hero_image_contact')" />

<section class="sec">
  <div class="wrap">
    <x-sec-head eyebrow="نحن هنا" title="ابقَ على" or="تواصل معنا">
      اترك بياناتك ويتواصل معك مهندس خلال ساعات العمل، أو تواصل مباشرةً عبر الهاتف والواتساب.
    </x-sec-head>

    <div class="contact-grid">
      <div style="display:grid;gap:16px">
        <div class="c-item rv">
          <div class="ic"><x-icon name="phone" /></div>
          <div><b>اتصال مباشر</b><a class="v" href="tel:{{ setting('phone') }}">{{ phone_pretty() }}</a></div>
        </div>
        <div class="c-item rv">
          <div class="ic"><x-icon name="whatsapp" /></div>
          <div><b>واتساب</b><a class="v" href="{{ whatsapp_url() }}">{{ phone_pretty() }}</a></div>
        </div>
        <div class="c-item rv">
          <div class="ic"><x-icon name="pin" /></div>
          <div><b>الموقع</b><span>{{ setting('address') }}</span></div>
        </div>
        <div class="c-item rv">
          <div class="ic"><x-icon name="clock" /></div>
          <div><b>أوقات العمل</b><span>{{ setting('hours_note') }}</span></div>
        </div>
      </div>

      <div class="form-card rv">
        <h3>اطلب عرض سعر</h3>
        <p class="hint">املأ البيانات ونعاود التواصل معك في أقرب وقت. المعاينة مجانية.</p>

        @if(session('sent'))
          <p class="hint" style="color:var(--orange-3);font-weight:700">
            تم استلام طلبك بنجاح، وسيتواصل معك أحد مهندسينا قريباً.
          </p>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" id="quote-form">
          @csrf
          <div class="f-row">
            <div class="f-field">
              <label for="name">الاسم</label>
              <input id="name" name="name" value="{{ old('name') }}" required maxlength="120" />
              @error('name')<small style="color:#C0392B">{{ $message }}</small>@enderror
            </div>
            <div class="f-field">
              <label for="phone">رقم الجوال</label>
              <input id="phone" name="phone" value="{{ old('phone') }}" required maxlength="30" inputmode="tel" dir="ltr" />
              @error('phone')<small style="color:#C0392B">{{ $message }}</small>@enderror
            </div>
          </div>
          <div class="f-field" style="margin-bottom:15px">
            <label for="service">الخدمة المطلوبة</label>
            <select id="service" name="service">
              <option value="">اختر الخدمة</option>
              @foreach($services as $service)
                <option value="{{ $service->title }}" @selected(old('service') === $service->title)>{{ $service->title }}</option>
              @endforeach
              <option value="عقد صيانة سنوي" @selected(old('service') === 'عقد صيانة سنوي')>عقد صيانة سنوي</option>
            </select>
          </div>
          <div class="f-field">
            <label for="details">تفاصيل الطلب</label>
            <textarea id="details" name="details" maxlength="2000">{{ old('details') }}</textarea>
          </div>
          {{-- مصيدة السبام: حقل مخفي لا يملؤه إلا الروبوت --}}
          <input type="text" name="website" value="" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px" aria-hidden="true" />
          <button type="submit" class="btn btn-primary">أرسل الطلب <x-icon name="arrow" /></button>
        </form>
      </div>
    </div>

    @if(setting('map_embed'))
      <div class="map-wrap rv">
        <iframe src="{{ setting('map_embed') }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="موقعنا على الخريطة"></iframe>
      </div>
    @endif
  </div>
</section>

<x-site.marquee />

<section class="sec bg-cream">
  <div class="wrap">
    <x-sec-head center eyebrow="أسئلة شائعة" title="أسئلة" or="تتكرر علينا" />
    <x-site.faq :faqs="$faqs" />
  </div>
</section>

<x-site.cta-band />

</x-layouts.site>
