<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('whatsapp_url')) {
    function whatsapp_url(?string $text = null): string
    {
        $url = 'https://wa.me/'.preg_replace('/\D+/', '', (string) setting('whatsapp'));

        return $text ? $url.'?text='.rawurlencode($text) : $url;
    }
}

if (! function_exists('phone_pretty')) {
    /** ‎+966503173407 → ‎+966 50 317 3407 */
    function phone_pretty(): string
    {
        $d = preg_replace('/\D+/', '', (string) setting('phone'));

        return preg_match('/^966(\d{2})(\d{3})(\d{4})$/', $d, $m)
            ? "+966 {$m[1]} {$m[2]} {$m[3]}"
            : (string) setting('phone');
    }
}

if (! function_exists('img')) {
    /**
     * الصور المرفوعة من اللوحة تُخزَّن في storage، وصور المحتوى المنقول
     * لا تزال روابط خارجية — هذه الدالة تتعامل مع الحالتين.
     */
    function img(?string $path, ?string $fallback = null): string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return $fallback ?? asset('logo.png');
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}

if (! function_exists('nav_links')) {
    /** روابط القائمة الرئيسية مع تحديد الصفحة الحالية. */
    function nav_links(): array
    {
        $links = [
            ['label' => 'الرئيسية', 'route' => 'home', 'pattern' => '/'],
            ['label' => 'نبذة عنا', 'route' => 'about', 'pattern' => 'about'],
            ['label' => 'خدماتنا', 'route' => 'services', 'pattern' => 'services*'],
            ['label' => 'أعمالنا', 'route' => 'gallery', 'pattern' => 'gallery'],
            ['label' => 'آراء العملاء', 'route' => 'testimonials', 'pattern' => 'testimonials'],
            ['label' => 'المقالات', 'route' => 'blog.index', 'pattern' => 'blog*'],
            ['label' => 'تواصل معنا', 'route' => 'contact', 'pattern' => 'contact'],
        ];

        return array_map(fn ($l) => [
            'label' => $l['label'],
            'url' => route($l['route']),
            'active' => request()->is($l['pattern']),
        ], $links);
    }
}
