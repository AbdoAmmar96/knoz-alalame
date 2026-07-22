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
        if ($page !== 'home') {
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
        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => Setting::get('site_name', 'شركة كنوز العالم للمصاعد'),
            'description' => config('seo.pages.home.description'),
            'telephone' => Setting::get('phone'),
            'url' => config('app.url'),
            'image' => asset('logo.png'),
            'priceRange' => '$$',
            'areaServed' => Setting::get('city', 'الرياض'),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => Setting::get('city', 'الرياض'),
                'addressCountry' => 'SA',
            ],
        ]);
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
