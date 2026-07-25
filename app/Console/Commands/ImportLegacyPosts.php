<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * يستورد كل مقالات الموقع القديم (konozcompany.com) عبر WordPress REST API
 * مع نصوصها وصورها، ويجعل رابط كل مقال الجديد مطابقاً لِمقطع رابطه القديم
 * (slug) حتى تحافظ المقالات على ترتيبها في جوجل عند رفع الموقع الحالي.
 */
class ImportLegacyPosts extends Command
{
    protected $signature = 'konoz:import-legacy {--prune : حذف المقالات غير الموجودة في الموقع القديم} {--limit=0 : حد أقصى للاختبار}';

    protected $description = 'استيراد كل مقالات الموقع القديم (نص + صورة) والحفاظ على روابطها';

    private string $api = 'https://konozcompany.com/wp-json/wp/v2/posts';

    public function handle(): int
    {
        Storage::disk('public')->makeDirectory('content/legacy');
        $slugs = [];
        $imported = 0;
        $imgOk = 0;
        $imgFail = 0;
        $limit = (int) $this->option('limit');

        for ($page = 1; $page <= 8; $page++) {
            $resp = Http::withoutVerifying()->timeout(60)->retry(2, 1500)
                ->withHeaders(['User-Agent' => 'Mozilla/5.0 KonozImporter'])
                ->get($this->api, ['per_page' => 25, 'page' => $page, '_embed' => 1]);

            if (! $resp->ok()) {
                break;
            }
            $posts = $resp->json();
            if (empty($posts)) {
                break;
            }

            foreach ($posts as $p) {
                $slug = rawurldecode((string) ($p['slug'] ?? ''));
                if ($slug === '') {
                    continue;
                }
                $slugs[] = $slug;

                $emb = $p['_embedded'] ?? [];
                $imgUrl = $emb['wp:featuredmedia'][0]['source_url'] ?? '';
                $cats = [];
                foreach (($emb['wp:term'] ?? []) as $group) {
                    foreach ((array) $group as $term) {
                        if (($term['taxonomy'] ?? '') === 'category' && ! empty($term['name'])) {
                            $cats[] = $term['name'];
                        }
                    }
                }

                $imagePath = $imgUrl ? $this->localizeImage($imgUrl) : null;
                if ($imgUrl) {
                    $imagePath ? $imgOk++ : $imgFail++;
                }

                Post::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'title' => $this->decode($p['title']['rendered'] ?? $slug),
                        'excerpt' => Str::limit(trim(strip_tags($p['excerpt']['rendered'] ?? '')), 300),
                        'body' => $this->sanitize($p['content']['rendered'] ?? ''),
                        'tag' => $cats[0] ?? 'مقالات',
                        'image' => $imagePath ?: ($imgUrl ?: null),
                        'source_url' => rawurldecode((string) ($p['link'] ?? '')),
                        'published_at' => isset($p['date']) ? Carbon::parse($p['date']) : now(),
                        'is_active' => true,
                    ]
                );
                $imported++;

                if ($limit && $imported >= $limit) {
                    break 2;
                }
            }
            $this->info("صفحة {$page}: إجمالي مستورد {$imported}");
        }

        if ($this->option('prune') && ! $limit) {
            $deleted = Post::whereNotIn('slug', array_unique($slugs))->delete();
            $this->warn("حُذف {$deleted} مقالاً ليس ضمن الموقع القديم");
        }

        $this->info("تم: مستورد={$imported} صور(نجاح={$imgOk} فشل={$imgFail}) روابط فريدة=".count(array_unique($slugs)));

        return self::SUCCESS;
    }

    /** يحمّل صورة المقال محلياً باسم مبني على رابطها (يتجاوزها إن وُجدت). */
    private function localizeImage(string $url): ?string
    {
        $name = 'content/legacy/'.md5($url).'.'.$this->ext($url);
        if (Storage::disk('public')->exists($name)) {
            return $name;
        }
        try {
            $r = Http::withoutVerifying()->timeout(45)
                ->withHeaders(['User-Agent' => 'Mozilla/5.0 KonozImporter'])->get($url);
            if (! $r->ok() || strlen($r->body()) < 200) {
                return null;
            }
            Storage::disk('public')->put($name, $r->body());

            return $name;
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function ext(string $url): string
    {
        $e = strtolower((string) pathinfo((string) parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));

        return in_array($e, ['jpg', 'jpeg', 'png', 'webp', 'gif']) ? $e : 'jpg';
    }

    private function decode(string $s): string
    {
        return trim(html_entity_decode($s, ENT_QUOTES, 'UTF-8'));
    }

    /** يبقي على وسوم المحتوى الأساسية فقط وينظّف السمات وروابط الصور الداخلية. */
    private function sanitize(string $html): string
    {
        $s = preg_replace('/<!--.*?-->/s', '', $html);
        $s = preg_replace('#<(script|style|iframe|form|noscript)[^>]*>.*?</\1>#is', '', $s);
        $s = preg_replace('/<img[^>]*>/i', '', $s);
        $s = strip_tags($s, '<p><h2><h3><h4><ul><ol><li><a><strong><em><b><i><br><blockquote>');
        // نبقي href فقط على الروابط، ونزيل بقية السمات من الوسوم الأخرى
        $s = preg_replace_callback('/<a\b[^>]*?href="([^"]*)"[^>]*>/i', fn ($m) => '<a href="'.$m[1].'">', $s);
        $s = preg_replace('/<(p|h2|h3|h4|ul|ol|li|strong|em|b|i|blockquote)\b[^>]*>/i', '<$1>', $s);
        $s = preg_replace('/(\s*<p>\s*<\/p>\s*)+/i', '', $s);
        $s = preg_replace('/\n{3,}/', "\n\n", $s);

        return trim($s);
    }
}
