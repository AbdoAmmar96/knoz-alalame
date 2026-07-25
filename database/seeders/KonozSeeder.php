<?php

namespace Database\Seeders;

use App\Models\ContractPoint;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\MarqueeItem;
use App\Models\Post;
use App\Models\ProcessStep;
use App\Models\Project;
use App\Models\Sector;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Stat;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * يزرع محتوى الموقع من ملفات JSON المستخرجة من النسخة الثابتة،
 * فيبقى النص العربي حرفياً كما اعتُمد بصرياً.
 */
class KonozSeeder extends Seeder
{
    private function data(string $file): array
    {
        $path = database_path("seeders/data/{$file}.json");

        return is_file($path) ? json_decode(file_get_contents($path), true) : [];
    }

    /** توليد slug عربي صالح للروابط (Str::slug يُفرغ العربية). */
    private function slug(string $text, string $fallback): string
    {
        $s = trim(preg_replace('/[\s_]+/u', '-', preg_replace('/[^\p{Arabic}\p{L}\p{N}\s-]+/u', '', $text)), '-');

        return $s !== '' ? $s : $fallback;
    }

    public function run(): void
    {
        /* ---------- مستخدم اللوحة ----------
           لا كلمة مرور ثابتة في الكود: المستودع عام، وأي كلمة مكتوبة هنا
           تصبح معروفة للجميع. تُقرأ من ADMIN_PASSWORD أو تُولَّد عشوائياً
           وتُطبع مرة واحدة عند الإنشاء. */
        $email = env('ADMIN_EMAIL', 'admin@konozcompany.com');

        if (! User::where('email', $email)->exists()) {
            $password = env('ADMIN_PASSWORD') ?: Str::password(16);

            User::create([
                'name' => env('ADMIN_NAME', 'مدير الموقع'),
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            $this->command?->newLine();
            $this->command?->warn('  أُنشئ حساب اللوحة — احفظ البيانات الآن، لن تظهر مرة أخرى:');
            $this->command?->line("    البريد      : {$email}");
            $this->command?->line("    كلمة المرور : {$password}");
            $this->command?->newLine();
        }

        /* ---------- الإعدادات ---------- */
        $groups = [
            'site_name' => 'general', 'phone' => 'contact', 'phone_display' => 'contact',
            'whatsapp' => 'contact', 'city' => 'contact', 'address' => 'contact',
            'hours_note' => 'contact', 'map_embed' => 'contact',
            'social_instagram' => 'social', 'social_facebook' => 'social',
            'social_x' => 'social', 'social_tiktok' => 'social',
            'social_snapchat' => 'social', 'social_youtube' => 'social',
            'footer_about' => 'general', 'copyright' => 'general',
            'hero_eyebrow' => 'hero', 'hero_title_1' => 'hero',
            'hero_title_2' => 'hero', 'hero_lead' => 'hero',
        ];
        foreach ($this->data('settings') as $key => $value) {
            Setting::updateOrCreate(['key' => $key], [
                'value' => $value,
                'group' => $groups[$key] ?? 'general',
            ]);
        }

        $about = $this->data('about');
        Setting::updateOrCreate(['key' => 'about_body'], [
            'value' => implode("\n\n", $about['paragraphs'] ?? []), 'group' => 'about',
        ]);
        Setting::updateOrCreate(['key' => 'about_values'], [
            'value' => implode("\n", $about['values'] ?? []), 'group' => 'about',
        ]);
        Setting::updateOrCreate(['key' => 'about_image'], [
            'value' => $about['image'] ?? '', 'group' => 'about',
        ]);

        foreach ($this->data('page_heroes') as $page => $hero) {
            Setting::updateOrCreate(['key' => "hero_image_{$page}"], [
                'value' => $hero['image'] ?? '', 'group' => 'heroes',
            ]);
        }

        /* ---------- الخدمات ----------
           الروابط إنجليزية بنص 02-architecture.md لأن جدول الـ301 مبني عليها. */
        $serviceSlugs = [
            'تركيب المصاعد' => 'installation',
            'صيانة المصاعد' => 'maintenance',
            'تحديث المصاعد القديمة' => 'modernization',
        ];
        foreach ($this->data('services') as $i => $row) {
            Service::updateOrCreate(
                ['slug' => $serviceSlugs[$row['title']] ?? $this->slug($row['title'], "service-{$i}")],
                [
                    'title' => $row['title'],
                    'body' => $row['body'],
                    'image' => $row['image'],
                    'features' => $row['features'],
                    'sort' => $row['sort'],
                ],
            );
        }

        /* ---------- القطاعات · المزايا · الخطوات ---------- */
        foreach ($this->data('sectors') as $row) {
            Sector::updateOrCreate(['title' => $row['title']], $row);
        }
        foreach ($this->data('features') as $row) {
            Feature::updateOrCreate(['title' => $row['title']], $row);
        }
        foreach ($this->data('steps') as $row) {
            ProcessStep::updateOrCreate(['title' => $row['title']], $row);
        }

        /* ---------- المشاريع ---------- */
        foreach ($this->data('projects') as $i => $row) {
            Project::updateOrCreate(
                ['slug' => $this->slug($row['title'], "project-{$i}")],
                ['title' => $row['title'], 'tag' => $row['tag'], 'image' => $row['image'], 'sort' => $row['sort']],
            );
        }

        /* ---------- المقالات ---------- */
        foreach ($this->data('posts') as $i => $row) {
            Post::updateOrCreate(
                ['slug' => $this->slug($row['title'], "post-{$i}")],
                [
                    'title' => $row['title'],
                    'excerpt' => $row['excerpt'],
                    'body' => $row['body'] ?? null,
                    'tag' => $row['tag'],
                    'image' => $row['image'],
                    'source_url' => $row['source_url'] ?? null,
                    'published_at' => now()->subDays(count($this->data('posts')) - $i),
                    'sort' => $row['sort'],
                ],
            );
        }

        /* ---------- بقية القوائم ---------- */
        foreach ($this->data('faqs') as $row) {
            Faq::updateOrCreate(['question' => $row['question']], $row);
        }
        // المفتاح sort لا name: قد يتغيّر الاسم (مثلاً من الموقع القديم).
        foreach ($this->data('testimonials') as $row) {
            Testimonial::updateOrCreate(['sort' => $row['sort']], $row);
        }
        // المفتاح sort لا label: عنوان الإحصائية قد يتغيّر، فالمطابقة عليه
        // تُنشئ صفاً مكرّراً بدل تحديث القائم.
        foreach ($this->data('stats') as $row) {
            Stat::updateOrCreate(['sort' => $row['sort']], $row);
        }
        foreach ($this->data('marquee') as $row) {
            MarqueeItem::updateOrCreate(['title' => $row['title']], $row);
        }
        foreach ($this->data('contract_points') as $row) {
            ContractPoint::updateOrCreate(['title' => $row['title']], $row);
        }
    }
}
