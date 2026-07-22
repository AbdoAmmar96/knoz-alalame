# موقع شركة كنوز العالم للمصاعد

موقع تعريفي عربي (RTL) بلوحة تحكم مخصّصة — **Laravel 13 + Blade**، بدون Filament وبدون أي حزمة لوحة تحكم.

---

## التشغيل محلياً

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite          # أو اضبط MySQL في .env
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

الموقع: `http://localhost:8000` · اللوحة: `http://localhost:8000/admin`

**بيانات الدخول الأولى** (غيّرها فوراً بعد النشر):

```
admin@konozcompany.com
konoz@2026
```

لتغيير كلمة المرور:

```bash
php artisan tinker
>>> App\Models\User::first()->update(['password' => Hash::make('كلمة-جديدة')]);
```

---

## البنية

```
app/
├── Admin/Resource.php              ← تعريف كل موارد اللوحة في ملف واحد
├── Http/Controllers/
│   ├── Site/                       ← SiteController · LeadController · SitemapController
│   └── Admin/                      ← Auth · Dashboard · Resource · Lead · Setting
├── Models/                         ← 14 موديل + Concerns/Sortable
└── Support/
    ├── Seo.php                     ← باني الميتا والـ JSON-LD
    └── helpers.php                 ← setting() · img() · whatsapp_url() · nav_links()

resources/views/
├── components/
│   ├── layouts/site.blade.php      ← الترويسة والفوتر والبريلودر
│   ├── layouts/admin.blade.php     ← هيكل اللوحة
│   ├── site/                       ← hero · marquee · service-card · sector-card ...
│   ├── icon.blade.php              ← أيقونات الموقع
│   └── admin-icon.blade.php        ← أيقونات اللوحة
├── site/                           ← صفحات الموقع
└── admin/                          ← صفحات اللوحة

public/assets/
├── style.css                       ← تصميم الموقع — منقول كما هو من النسخة الثابتة
├── app.js                          ← سكربت الموقع — منقول كما هو
└── admin.css                       ← تنسيق اللوحة

routes/
├── web.php · admin.php
└── redirects.php                   ← جدول الـ301 من روابط الموقع القديم
```

### لماذا Blade لا Inertia/React

`02-architecture.md` يذكر Inertia + React مع SSR. اعتُمد **Blade** بقرار من العميل: التصميم مكتوب أصلاً CSS/JS خام ومُختبَر بصرياً، وBlade ينقله حرفياً بلا إعادة كتابة، ويعطي SSR افتراضياً بلا عملية Node على السيرفر. وهو المسار البديل الموثّق في القسم 7 من ملف المعمارية نفسه.

---

## اللوحة

| القسم | الوظيفة |
|---|---|
| لوحة البيانات | عدّادات المحتوى + أحدث الطلبات |
| طلبات العملاء | فلترة بالحالة · واتساب/اتصال بنقرة · تصدير CSV |
| الخدمات · القطاعات · المزايا · خطوات العمل · المشاريع · المقالات · آراء العملاء · الأسئلة · الأرقام · الشريط المتحرك · بنود العقد | إضافة/تعديل/حذف · إظهار وإخفاء · **ترتيب بالسحب والإفلات** · رفع صور |
| إعدادات الموقع | أرقام التواصل · نصوص الواجهة · صور الترويسات · رابط الخريطة |

### إضافة نوع محتوى جديد

عدّل `app/Admin/Resource.php` فقط — الكنترولر والقوالب مشتركة:

```php
'partners' => [
    'label' => 'الشركاء', 'singular' => 'شريك',
    'model' => Partner::class, 'icon' => 'grid',
    'columns' => ['title' => 'الشريك'],
    'fields' => [
        'title' => ['label' => 'الاسم', 'type' => 'text', 'rules' => 'required|max:150'],
        'image' => ['label' => 'الشعار', 'type' => 'image'],
    ],
],
```

أنواع الحقول: `text · textarea · number · datetime · image · slug · list · svg · bool`

---

## SEO

- ميتا + Open Graph + canonical لكل صفحة من `config/seo.php`
- JSON-LD: `LocalBusiness` في كل صفحة · `Service` في صفحات الخدمات · `FAQPage` في التواصل والعقود · `Article` في المقالات · `BreadcrumbList` في الصفحات الداخلية
- `/sitemap.xml` و `/robots.txt` ديناميكيان

> ⚠ **حُذف `public/robots.txt` عمداً** — وجوده يحجب الراوت ويجعل الملف ثابتاً فارغاً. لا تُعده.

### الـ301

`routes/redirects.php` يغطي الروابط العربية التي ظهرت في مسح الموقع القديم، وأي رابط مقال قديم يُطابَق تلقائياً بحقل `source_url`.
**قبل الإطلاق:** صدّر كل الروابط المفهرسة من Search Console وأضف الناقص إلى `$map`.

---

## الرفع على السيرفر

الحزمة الجاهزة في `konoz-deploy/` — تتضمّن `vendor/` فلا تحتاج Composer على السيرفر.

### 1. ارفع الملفات

**الوضع المفضّل** — المشروع خارج جذر الويب:

```
/home/user/konoz/          ← المشروع كاملاً
/home/user/public_html/    ← اجعله يشير إلى /home/user/konoz/public
```

ثم **احذف** ملف `.htaccess` من جذر المشروع (غير مطلوب في هذه الحالة).

**البديل** — استضافة لا تسمح بتغيير جذر الدومين: ارفع كل شيء داخل `public_html`،
وملف `.htaccess` في الجذر سيحوّل الطلبات إلى `public` ويحجب الملفات الحساسة.

### 2. الإعدادات

```bash
cp .env.production.example .env
nano .env                      # الدومين وبيانات قاعدة البيانات
php artisan key:generate
```

> **APP_URL يحدّد البروتوكول.** اكتبه `https://` فقط بعد تركيب شهادة SSL —
> كتابته https قبل الشهادة يكسر روابط الصور وملفات التنسيق.

### 3. التجهيز

```bash
php artisan konoz:deploy          # هجرات + storage:link + كاش الإنتاج
php artisan db:seed               # مرة واحدة فقط، لزرع المحتوى
php artisan konoz:deploy --check  # فحص الجاهزية
```

`--check` يفحص 13 نقطة: مفتاح التطبيق · الديباج · الدومين · قاعدة البيانات ·
الصلاحيات · الروابط · كلمة مرور المدير · الكوكي الآمن · الصور المحلية.

### 4. الصلاحيات

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache   # حسب مستخدم السيرفر
```

### 5. بعد تركيب SSL

- أزل التعليق عن سطري فرض HTTPS في `public/.htaccess`
- `SESSION_SECURE_COOKIE=true` في `.env`
- `APP_URL=https://...`
- `php artisan config:cache`

### 6. أخيراً

1. **غيّر كلمة مرور المدير** من: اللوحة › حسابي
2. أرسل `sitemap.xml` في Search Console وراقب تحوّل الروابط القديمة
3. تأكد أن `php artisan konoz:deploy --check` يعطي «جاهز للإطلاق»

### عند أي تحديث لاحق

```bash
php artisan optimize:clear && php artisan konoz:deploy
```

### بنود مفتوحة

- **الصور**: كل صور المحتوى المنقولة روابط خارجية من الموقع القديم بمقاس ≤225px. حقل الصورة في اللوحة يقبل الرفع المباشر — استبدلها بصور حقيقية ≥1600px.
- **بيانات لم يؤكدها العميل** (تُعدَّل من اللوحة › الإعدادات والأرقام): سنوات الخبرة · عدد المشاريع · عدد المهندسين · العنوان الكامل · الإيميل الرسمي.
- **المقالات**: نُقلت عناوينها ومقتطفاتها فقط وتُحوَّل لروابط الموقع القديم. عند كتابة النص الكامل في حقل «نص المقال» تفتح على الموقع الجديد تلقائياً وتدخل الـsitemap.
