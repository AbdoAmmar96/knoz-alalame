<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    /** مجموعات الإعدادات كما تظهر في اللوحة. */
    public static function schema(): array
    {
        return [
            'general' => ['label' => 'عام', 'fields' => [
                'site_name' => ['label' => 'اسم الشركة', 'type' => 'text'],
                'footer_about' => ['label' => 'نبذة الفوتر', 'type' => 'textarea'],
                'copyright' => ['label' => 'سطر الحقوق', 'type' => 'text'],
            ]],
            'contact' => ['label' => 'بيانات التواصل', 'fields' => [
                'phone' => ['label' => 'رقم الهاتف (بصيغة دولية)', 'type' => 'text', 'hint' => 'مثال: +966503173407'],
                'phone_display' => ['label' => 'الرقم كما يظهر في الترويسة', 'type' => 'text'],
                'whatsapp' => ['label' => 'رقم الواتساب (أرقام فقط)', 'type' => 'text', 'hint' => 'مثال: 966503173407'],
                'city' => ['label' => 'المدينة', 'type' => 'text'],
                'address' => ['label' => 'العنوان', 'type' => 'text'],
                'hours_note' => ['label' => 'ملاحظة أوقات العمل', 'type' => 'text'],
                'map_embed' => ['label' => 'رابط خريطة جوجل (src الخاص بالـ iframe)', 'type' => 'textarea'],
            ]],
            'social' => ['label' => 'روابط التواصل الاجتماعي', 'fields' => [
                'social_instagram' => ['label' => 'إنستغرام', 'type' => 'text', 'hint' => 'رابط الحساب كاملاً'],
                'social_tiktok' => ['label' => 'تيك توك', 'type' => 'text'],
                'social_snapchat' => ['label' => 'سناب شات', 'type' => 'text'],
                'social_facebook' => ['label' => 'فيسبوك', 'type' => 'text'],
                'social_x' => ['label' => 'إكس (تويتر)', 'type' => 'text'],
                'social_youtube' => ['label' => 'يوتيوب', 'type' => 'text'],
            ]],
            'hero' => ['label' => 'واجهة الرئيسية', 'fields' => [
                'hero_eyebrow' => ['label' => 'السطر العلوي', 'type' => 'text'],
                'hero_title_1' => ['label' => 'العنوان — السطر الأول', 'type' => 'text'],
                'hero_title_2' => ['label' => 'العنوان — السطر الثاني (برتقالي)', 'type' => 'text'],
                'hero_lead' => ['label' => 'الفقرة التعريفية', 'type' => 'textarea'],
            ]],
            'about' => ['label' => 'نبذة عنا', 'fields' => [
                'about_body' => ['label' => 'نص النبذة', 'type' => 'textarea', 'rows' => 8, 'hint' => 'افصل بين الفقرات بسطر فارغ.'],
                'about_values' => ['label' => 'قيم الشركة', 'type' => 'textarea', 'hint' => 'قيمة في كل سطر.'],
                'about_image' => ['label' => 'صورة النبذة', 'type' => 'image'],
            ]],
            'heroes' => ['label' => 'صور ترويسات الصفحات', 'fields' => [
                'hero_image_about' => ['label' => 'نبذة عنا', 'type' => 'image'],
                'hero_image_services' => ['label' => 'خدماتنا', 'type' => 'image'],
                'hero_image_gallery' => ['label' => 'أعمالنا', 'type' => 'image'],
                'hero_image_blog' => ['label' => 'المقالات', 'type' => 'image'],
                'hero_image_contact' => ['label' => 'تواصل معنا', 'type' => 'image'],
            ]],
        ];
    }

    public function edit()
    {
        return view('admin.settings', ['schema' => static::schema()]);
    }

    public function update(Request $request): RedirectResponse
    {
        foreach (static::schema() as $group => $section) {
            foreach ($section['fields'] as $key => $field) {
                if ($field['type'] === 'image') {
                    if ($request->hasFile($key.'_file')) {
                        $old = Setting::get($key);
                        Setting::put($key, $request->file($key.'_file')->store('uploads', 'public'), $group);
                        if ($old && ! Str::startsWith($old, ['http://', 'https://'])) {
                            Storage::disk('public')->delete($old);
                        }

                        continue;
                    }
                }
                if ($request->has($key)) {
                    Setting::put($key, (string) $request->input($key), $group);
                }
            }
        }

        return back()->with('ok', 'تم حفظ الإعدادات.');
    }
}
