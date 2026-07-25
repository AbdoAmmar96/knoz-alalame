<?php

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| تحويلات 301 من روابط الموقع القديم (konozcompany.com)
|--------------------------------------------------------------------------
| الموقع مفهرس في جوجل؛ كل رابط عربي قديم يجب أن يتحوّل 301 لمقابله الجديد
| حتى لا نخسر ترتيبه عند رفع الموقع الحالي (konoz-master-plan.md — القسم 6).
|
| الروابط القديمة كانت تنتهي بشرطة مائلة (/الرابط/)؛ نعالج النسختين — بشرطة
| وبدونها — بترميز عربي سليم وفي تحويلة 301 واحدة بلا سلاسل.
|
| ⚠ القائمة تغطي ما ظهر في مسح الموقع القديم. عند تصدير كل الروابط من
|   Search Console تُضاف البقية للخريطة أدناه وحدها دون لمس بقية الكود.
*/

$map = [
    // الصفحات الرئيسية
    'نبذة-عنا' => 'about',
    'من-نحن' => 'about',
    'خدماتنا' => 'services',
    'خدماتنا-في-كنوز-العالم' => 'services',
    'معرض-الصور' => 'gallery',
    'معرض-الاعمال' => 'gallery',
    'اتصل-بنا' => 'contact',
    'تواصل-معنا' => 'contact',
    'المدونة' => 'blog.index',

    // صفحات الخدمات المفردة
    'تركيب-مصاعد' => ['services.show', 'installation'],
    'تركيب-المصاعد' => ['services.show', 'installation'],
    'صيانة-مصاعد' => ['services.show', 'maintenance'],
    'صيانة-المصاعد' => ['services.show', 'maintenance'],
    'تحديث-المصاعد' => ['services.show', 'modernization'],
    'عقود-الصيانة' => 'contracts',

    // صفحات الموقع القديم كما ظهرت في خريطته (page-sitemap)
    'نموذج-التواصل-السريع' => 'contact',
    'اتصل-بنا-شركه-كنوز-للمصاعد' => 'contact',
    'إتمام-الطلب-شركة-كنوز-العالم' => 'contact',
    'call' => 'contact',
    'نبذة-عنا-شركة-كنوز-العالم-لصيانة-وتركي' => 'about',
    'خدماتنا-في-كنوز-العالم-لصيانة-وتركيب-ا' => 'services',
    'متجر-أنظمة-مصاعد-بالرياض' => 'services',
    'المتجر-الرسمي-لشركة-كنوز-العالم' => 'services',
    'مقارنة-منتجات-المصاعد' => 'services',
    'مقارنة-منتجات-شركة-كنوز-العالم-لتركيب' => 'services',
    'معرض-الصور-شركة-كنوز-العالم-صيانة-مصاع' => 'gallery',
    'الأسئلة-الشائعة' => 'contracts',
    'مسودة-تلقائيةالمقالات-صيانة-مصاعد-با' => 'blog.index',
    'حسابي-في-كنوز-العالم' => 'home',
    'سلة-المشتريات' => 'home',
    'قائمة-الرغبات-تركيب-وصيانه-المصاعد-با' => 'home',
];

/** يبني تحويلة 301 لهدف من الخريطة (اسم مسار، أو [مسار، باراميتر خدمة]). */
$redirectTo = function ($target) {
    [$name, $param] = is_array($target) ? $target : [$target, null];

    return redirect()->route($name, $param ? ['service' => $param] : [], 301);
};

// أقسام كاملة من الموقع القديم (بورتفوليو/تصنيفات/وسوم/مؤلف) ⇐ أقرب صفحة
Route::get('/projects/{any?}', fn () => redirect()->route('gallery', [], 301))->where('any', '.*');
Route::get('/category/{any?}', fn () => redirect()->route('blog.index', [], 301))->where('any', '.*');
Route::get('/author/{any?}', fn () => redirect()->route('home', [], 301))->where('any', '.*');

/*
 | الاحتياطي يعالج كل روابط الموقع القديم (بشرطة أو بدونها) بترميز UTF-8 سليم.
 | الترتيب مهم: المقال المنقول له الأولوية على خريطة الصفحات حتى لا يُختطف
 | رابط مقال (مثل «صيانة-مصاعد») إلى صفحة خدمة.
 */
Route::fallback(function (Request $request) use ($map, $redirectTo) {
    $path = rawurldecode(trim($request->path(), '/'));

    // 1) مقال منقول رابطه مطابق للـslug (الأولوية للمحتوى)
    $post = Post::query()->where('slug', $path)->where('is_active', true)->first();
    if ($post) {
        return redirect()->route('blog.show', $post, 301);
    }

    // 2) صفحات الموقع القديم (تشمل النسخة المنتهية بشرطة)
    if (isset($map[$path])) {
        return $redirectTo($map[$path]);
    }

    // 3) مقال قديم بِslug مختلف — مطابقة مقطع المسار داخل source_url
    $post = Post::query()
        ->whereNotNull('source_url')
        ->where('source_url', 'like', '%/'.$path.'%')
        ->first();

    if ($post && $post->body) {
        return redirect()->route('blog.show', $post, 301);
    }

    abort(404);
});
