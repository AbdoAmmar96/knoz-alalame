<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Collection;

/**
 * مصدر واحد للميتا والـ JSON-LD.
 * الطبقة سيرفرية بالكامل (Blade) فتعمل حتى لو تعطّل أي جافاسكربت.
 */
class Seo
{
    public static function for(string $page, array $extra = []): array
    {
        $base = config("seo.pages.$page", []);

        $jsonld = array_merge([self::localBusiness()], $extra['jsonld'] ?? []);
        if ($page === 'home') {
            $jsonld[] = self::website();
        } else {
            $jsonld[] = self::breadcrumb($extra['crumbs'] ?? []);
        }

        return [
            'title' => $extra['title'] ?? ($base['title'] ?? Setting::get('site_name')),
            'description' => $extra['description'] ?? ($base['description'] ?? ''),
            'canonical' => $extra['canonical'] ?? url()->current(),
            'image' => $extra['image'] ?? asset('logo.png'),
            'robots' => $extra['robots'] ?? 'index,follow',
            'jsonld' => array_values(array_filter($jsonld)),
        ];
    }

    public static function localBusiness(): array
    {
        $url = rtrim(config('app.url'), '/');
        $city = Setting::get('city', 'الرياض');
        $phone = Setting::get('phone');

        // روابط الحسابات الرسمية (تساعد جوجل على ربط الموقع بملف النشاط التجاري)
        $sameAs = array_values(array_filter([
            Setting::get('social_instagram'),
            Setting::get('social_tiktok'),
            Setting::get('social_snapchat'),
            Setting::get('social_facebook'),
            Setting::get('social_x'),
            Setting::get('social_youtube'),
        ]));

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            '@id' => $url.'/#business',
            'name' => Setting::get('site_name', 'شركة كنوز العالم للمصاعد'),
            'alternateName' => 'كنوز العالم للمصاعد',
            'description' => config('seo.pages.home.description'),
            'slogan' => 'تركيب وصيانة وتحديث المصاعد بالرياض',
            'telephone' => $phone,
            'url' => $url,
            'image' => asset('logo.png'),
            'logo' => asset('logo.png'),
            'priceRange' => '$$',
            'currenciesAccepted' => 'SAR',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => Setting::get('address', 'الرياض'),
                'addressLocality' => $city,
                'addressRegion' => 'منطقة الرياض',
                'addressCountry' => 'SA',
            ],
            'areaServed' => ['@type' => 'City', 'name' => $city],
            'openingHoursSpecification' => [[
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'opens' => '00:00',
                'closes' => '23:59',
            ]],
            'contactPoint' => array_filter([
                '@type' => 'ContactPoint',
                'telephone' => $phone,
                'contactType' => 'customer service',
                'areaServed' => 'SA',
                'availableLanguage' => ['Arabic'],
            ]),
            'knowsAbout' => ['تركيب مصاعد', 'صيانة مصاعد', 'تحديث مصاعد', 'عقود صيانة المصاعد', 'مصاعد ركاب', 'مصاعد بضائع', 'مصاعد بانوراما'],
            'sameAs' => $sameAs ?: null,
        ]);
    }

    /** مخطط الموقع (يساعد على ظهور اسم الموقع كعلامة معروفة). */
    public static function website(): array
    {
        $url = rtrim(config('app.url'), '/');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => $url.'/#website',
            'name' => Setting::get('site_name', 'شركة كنوز العالم للمصاعد'),
            'url' => $url,
            'inLanguage' => 'ar',
            'publisher' => ['@id' => $url.'/#business'],
        ];
    }

    /** مسار التنقّل — يساعد جوجل على عرض المسار بدل الرابط الخام. */
    public static function breadcrumb(array $crumbs): ?array
    {
        if (! $crumbs) {
            return null;
        }

        $items = [['name' => 'الرئيسية', 'url' => route('home')]];
        foreach ($crumbs as $name => $url) {
            $items[] = ['name' => $name, 'url' => $url];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)->values()->map(fn ($c, $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $c['name'],
                'item' => $c['url'],
            ])->all(),
        ];
    }

    /** يُبنى من جدول الأسئلة الشائعة مباشرة. */
    public static function faqPage(Collection $faqs): ?array
    {
        if ($faqs->isEmpty()) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqs->map(fn ($f) => [
                '@type' => 'Question',
                'name' => $f->question,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f->answer],
            ])->all(),
        ];
    }

    public static function service(string $name, string $description): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $name,
            'description' => $description,
            'serviceType' => $name,
            'areaServed' => ['@type' => 'City', 'name' => Setting::get('city', 'الرياض')],
            'provider' => [
                '@type' => 'LocalBusiness',
                'name' => Setting::get('site_name'),
                'telephone' => Setting::get('phone'),
            ],
        ];
    }

    public static function article(\App\Models\Post $post): array
    {
        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->excerpt,
            'image' => $post->image,
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at?->toIso8601String(),
            'author' => ['@type' => 'Organization', 'name' => Setting::get('site_name')],
            'publisher' => [
                '@type' => 'Organization',
                'name' => Setting::get('site_name'),
                'logo' => ['@type' => 'ImageObject', 'url' => asset('logo.png')],
            ],
        ]);
    }
}
