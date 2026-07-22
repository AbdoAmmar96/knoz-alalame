<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| تحويلات 301 من روابط الموقع القديم
|--------------------------------------------------------------------------
| الموقع مفهرس منذ 2025؛ كل رابط عربي قديم يجب أن يتحول 301 لمقابله الجديد
| حتى لا نخسر ترتيبه (konoz-master-plan.md — القسم 6).
|
| ⚠ القائمة تغطي ما ظهر في مسح الموقع القديم. عند تصدير كل الروابط من
|   Search Console تُضاف البقية هنا وحدها دون لمس بقية الكود.
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
];

foreach ($map as $old => $target) {
    Route::get('/'.$old, function () use ($target) {
        [$name, $param] = is_array($target) ? $target : [$target, null];

        return redirect()->route($name, $param ? ['service' => $param] : [], 301);
    });
}

/*
 | المقالات القديمة: أي رابط عربي غير مطابق لما سبق يُبحث عنه في جدول المقالات
 | بحقل source_url — فإن وُجد يُحوَّل لصفحته الجديدة، وإلا يستمر لصفحة 404.
 */
Route::fallback(function (Illuminate\Http\Request $request) {
    $post = App\Models\Post::query()
        ->whereNotNull('source_url')
        ->where('source_url', 'like', '%'.rawurldecode(trim($request->path(), '/')).'%')
        ->first();

    if ($post && $post->body) {
        return redirect()->route('blog.show', $post, 301);
    }

    abort(404);
});
