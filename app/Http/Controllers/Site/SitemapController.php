<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [
            ['loc' => route('home'), 'priority' => '1.0', 'freq' => 'weekly'],
            ['loc' => route('about'), 'priority' => '0.7', 'freq' => 'monthly'],
            ['loc' => route('services'), 'priority' => '0.9', 'freq' => 'monthly'],
            ['loc' => route('contracts'), 'priority' => '0.9', 'freq' => 'monthly'],
            ['loc' => route('gallery'), 'priority' => '0.7', 'freq' => 'monthly'],
            ['loc' => route('testimonials'), 'priority' => '0.6', 'freq' => 'monthly'],
            ['loc' => route('blog.index'), 'priority' => '0.7', 'freq' => 'weekly'],
            ['loc' => route('contact'), 'priority' => '0.8', 'freq' => 'monthly'],
        ];

        foreach (Service::live() as $service) {
            $urls[] = [
                'loc' => route('services.show', $service),
                'priority' => '0.9',
                'freq' => 'monthly',
                'lastmod' => $service->updated_at?->toAtomString(),
            ];
        }

        foreach (Project::live() as $project) {
            $urls[] = [
                'loc' => route('gallery.show', $project),
                'priority' => '0.6',
                'freq' => 'monthly',
                'lastmod' => $project->updated_at?->toAtomString(),
            ];
        }

        // المقالات بلا نص كامل تُحوَّل خارجياً، فلا تُدرج في الخريطة
        foreach (Post::query()->published()->whereNotNull('body')->get() as $post) {
            $urls[] = [
                'loc' => route('blog.show', $post),
                'priority' => '0.6',
                'freq' => 'monthly',
                'lastmod' => $post->updated_at?->toAtomString(),
            ];
        }

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /storage/tmp',
            '',
            'Sitemap: '.route('sitemap'),
        ];

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=utf-8']);
    }
}
