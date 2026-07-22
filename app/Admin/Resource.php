<?php

namespace App\Admin;

use App\Models\ContractPoint;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\MarqueeItem;
use App\Models\ProcessStep;
use App\Models\Project;
use App\Models\Post;
use App\Models\Sector;
use App\Models\Service;
use App\Models\Stat;
use App\Models\Testimonial;

/**
 * تعريف موارد لوحة التحكم في مكان واحد.
 * كنترولر واحد وقالبان يخدمون كل الجداول بدل تكرار CRUD إحدى عشرة مرة.
 *
 * أنواع الحقول: text · textarea · number · image · slug · list · svg · bool · select
 */
class Resource
{
    public static function all(): array
    {
        return [

            'services' => [
                'label' => 'الخدمات',
                'singular' => 'خدمة',
                'model' => Service::class,
                'icon' => 'grid',
                'columns' => ['title' => 'الخدمة', 'slug' => 'الرابط'],
                'fields' => [
                    'title' => ['label' => 'اسم الخدمة', 'type' => 'text', 'rules' => 'required|string|max:150'],
                    'slug' => ['label' => 'الرابط (إنجليزي)', 'type' => 'slug', 'rules' => 'required|alpha_dash|max:150', 'hint' => 'يظهر في العنوان: /services/الرابط — تغييره يكسر الروابط المفهرسة.'],
                    'image' => ['label' => 'الصورة', 'type' => 'image'],
                    'body' => ['label' => 'الوصف المختصر', 'type' => 'textarea', 'rules' => 'nullable|string|max:600'],
                    'long_body' => ['label' => 'الشرح الكامل (صفحة الخدمة)', 'type' => 'textarea', 'rows' => 8, 'hint' => 'افصل بين الفقرات بسطر فارغ.'],
                    'features' => ['label' => 'النقاط المميزة', 'type' => 'list', 'hint' => 'نقطة في كل سطر.'],
                ],
            ],

            'sectors' => [
                'label' => 'القطاعات',
                'singular' => 'قطاع',
                'model' => Sector::class,
                'icon' => 'layers',
                'columns' => ['number' => 'الرقم', 'title' => 'القطاع'],
                'fields' => [
                    'number' => ['label' => 'الرقم الظاهر', 'type' => 'text', 'rules' => 'nullable|string|max:8', 'hint' => 'مثل: 01'],
                    'title' => ['label' => 'اسم القطاع', 'type' => 'text', 'rules' => 'required|string|max:150'],
                    'body' => ['label' => 'الوصف', 'type' => 'textarea'],
                    'image' => ['label' => 'صورة الخلفية', 'type' => 'image'],
                    'icon' => ['label' => 'الأيقونة (SVG)', 'type' => 'svg'],
                ],
            ],

            'features' => [
                'label' => 'المزايا',
                'singular' => 'ميزة',
                'model' => Feature::class,
                'icon' => 'star',
                'columns' => ['title' => 'الميزة'],
                'fields' => [
                    'title' => ['label' => 'العنوان', 'type' => 'text', 'rules' => 'required|string|max:150'],
                    'body' => ['label' => 'الوصف', 'type' => 'textarea'],
                    'icon' => ['label' => 'الأيقونة (SVG)', 'type' => 'svg'],
                ],
            ],

            'process-steps' => [
                'label' => 'خطوات العمل',
                'singular' => 'خطوة',
                'model' => ProcessStep::class,
                'icon' => 'route',
                'columns' => ['title' => 'الخطوة'],
                'fields' => [
                    'title' => ['label' => 'عنوان الخطوة', 'type' => 'text', 'rules' => 'required|string|max:150'],
                    'body' => ['label' => 'الوصف', 'type' => 'textarea'],
                    'icon' => ['label' => 'الأيقونة (SVG)', 'type' => 'svg'],
                ],
            ],

            'projects' => [
                'label' => 'المشاريع',
                'singular' => 'مشروع',
                'model' => Project::class,
                'icon' => 'image',
                'columns' => ['title' => 'المشروع', 'tag' => 'التصنيف'],
                'fields' => [
                    'title' => ['label' => 'اسم المشروع', 'type' => 'text', 'rules' => 'required|string|max:200'],
                    'slug' => ['label' => 'الرابط', 'type' => 'slug', 'rules' => 'nullable|string|max:200', 'from' => 'title'],
                    'tag' => ['label' => 'التصنيف', 'type' => 'text', 'rules' => 'nullable|string|max:60', 'hint' => 'سكني / تجاري / صناعي / تحديث'],
                    'image' => ['label' => 'الصورة', 'type' => 'image'],
                ],
            ],

            'posts' => [
                'label' => 'المقالات',
                'singular' => 'مقال',
                'model' => Post::class,
                'icon' => 'doc',
                'columns' => ['title' => 'المقال', 'tag' => 'التصنيف'],
                'fields' => [
                    'title' => ['label' => 'عنوان المقال', 'type' => 'text', 'rules' => 'required|string|max:200'],
                    'slug' => ['label' => 'الرابط', 'type' => 'slug', 'rules' => 'nullable|string|max:200', 'from' => 'title'],
                    'tag' => ['label' => 'التصنيف', 'type' => 'text', 'rules' => 'nullable|string|max:60'],
                    'image' => ['label' => 'صورة المقال', 'type' => 'image'],
                    'excerpt' => ['label' => 'المقتطف', 'type' => 'textarea', 'rules' => 'nullable|string|max:500'],
                    'body' => ['label' => 'نص المقال', 'type' => 'textarea', 'rows' => 14, 'hint' => 'افصل بين الفقرات بسطر فارغ. اتركه فارغاً ليفتح المقال على رابط المصدر.'],
                    'source_url' => ['label' => 'رابط المصدر (اختياري)', 'type' => 'text', 'rules' => 'nullable|url|max:500'],
                    'published_at' => ['label' => 'تاريخ النشر', 'type' => 'datetime'],
                ],
            ],

            'testimonials' => [
                'label' => 'آراء العملاء',
                'singular' => 'رأي',
                'model' => Testimonial::class,
                'icon' => 'quote',
                'columns' => ['name' => 'العميل', 'role' => 'الصفة', 'rating' => 'التقييم'],
                'fields' => [
                    'name' => ['label' => 'اسم العميل', 'type' => 'text', 'rules' => 'required|string|max:120'],
                    'role' => ['label' => 'الصفة', 'type' => 'text', 'rules' => 'nullable|string|max:120'],
                    'body' => ['label' => 'نص الرأي', 'type' => 'textarea', 'rules' => 'required|string|max:800'],
                    'rating' => ['label' => 'عدد النجوم', 'type' => 'number', 'rules' => 'required|integer|min:1|max:5', 'default' => 5],
                ],
            ],

            'faqs' => [
                'label' => 'الأسئلة الشائعة',
                'singular' => 'سؤال',
                'model' => Faq::class,
                'icon' => 'help',
                'columns' => ['question' => 'السؤال'],
                'fields' => [
                    'question' => ['label' => 'السؤال', 'type' => 'text', 'rules' => 'required|string|max:250'],
                    'answer' => ['label' => 'الإجابة', 'type' => 'textarea', 'rules' => 'required|string|max:1500'],
                ],
            ],

            'stats' => [
                'label' => 'الأرقام',
                'singular' => 'رقم',
                'model' => Stat::class,
                'icon' => 'chart',
                'columns' => ['value' => 'القيمة', 'label' => 'الوصف'],
                'fields' => [
                    'value' => ['label' => 'القيمة', 'type' => 'text', 'rules' => 'required|string|max:20', 'hint' => 'رقم مثل 130، أو نص مثل 24/7'],
                    'suffix' => ['label' => 'لاحقة', 'type' => 'text', 'rules' => 'nullable|string|max:8', 'hint' => 'مثل +'],
                    'is_counter' => ['label' => 'يعدّ تصاعدياً عند الظهور', 'type' => 'bool'],
                    'label' => ['label' => 'الوصف', 'type' => 'text', 'rules' => 'required|string|max:120'],
                ],
            ],

            'marquee-items' => [
                'label' => 'الشريط المتحرك',
                'singular' => 'عبارة',
                'model' => MarqueeItem::class,
                'icon' => 'marquee',
                'columns' => ['title' => 'العبارة'],
                'fields' => [
                    'title' => ['label' => 'العبارة', 'type' => 'text', 'rules' => 'required|string|max:120'],
                ],
            ],

            'contract-points' => [
                'label' => 'بنود عقد الصيانة',
                'singular' => 'بند',
                'model' => ContractPoint::class,
                'icon' => 'check',
                'columns' => ['title' => 'البند'],
                'fields' => [
                    'title' => ['label' => 'البند', 'type' => 'text', 'rules' => 'required|string|max:200'],
                ],
            ],
        ];
    }

    public static function find(string $key): array
    {
        $all = static::all();
        abort_unless(isset($all[$key]), 404);

        return $all[$key] + ['key' => $key];
    }
}
