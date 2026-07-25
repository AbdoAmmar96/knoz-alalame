<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * يجلب نصّ كل مقال من موقع كنوز القديم (ووردبريس) عبر REST API ويخزّنه محلياً،
 * فتُفتح المقالات داخل الموقع الجديد بدل التحويل للرابط القديم.
 *
 * الاستخراج يقتصر على وسوم آمنة (عناوين/فقرات/قوائم) بلا سمات، فالتخزين
 * قابل للعرض مباشرةً دون خطر حقن.
 */
class FetchPosts extends Command
{
    protected $signature = 'konoz:fetch-posts
                            {--insecure : تخطّي التحقق من شهادة TLS}
                            {--force : إعادة الجلب حتى لو كان للمقال نصّ}
                            {--write-seed : تحديث ملف بذور المقالات بالنصوص}';

    protected $description = 'جلب نصوص المقالات من الموقع القديم إلى قاعدة البيانات';

    private string $api = 'https://konozcompany.com/wp-json/wp/v2/posts';

    public function handle(): int
    {
        $updated = 0;
        $failed = [];

        foreach (Post::orderBy('sort')->get() as $post) {
            if ($post->body && ! $this->option('force')) {
                $this->line("  <fg=gray>موجود سلفاً</> {$post->title}");

                continue;
            }
            if (! $post->source_url) {
                continue;
            }

            $wp = $this->lookup($post);
            if (! $wp) {
                $failed[$post->title] = 'لم يُعثر على المقال في الموقع القديم';
                $this->line("  <fg=red>✘</> {$post->title} — لا مطابقة");

                continue;
            }

            $body = $this->extract($wp['content']['rendered'] ?? '', $post->title);
            if (mb_strlen(strip_tags($body)) < 400) {
                $failed[$post->title] = 'النصّ المُستخرج قصير جداً';
                $this->line("  <fg=red>✘</> {$post->title} — نصّ قصير");

                continue;
            }

            $excerpt = $post->excerpt ?: $this->clean($wp['excerpt']['rendered'] ?? '');
            $post->update([
                'body' => $body,
                'excerpt' => \Illuminate\Support\Str::limit($excerpt, 300),
            ]);
            $updated++;
            $this->line("  <fg=green>✔</> {$post->title}  <fg=gray>(".mb_strlen(strip_tags($body))." حرف)</>");
        }

        $this->newLine();
        $this->info("حُدّث {$updated} مقالاً".($failed ? " — فشل ".count($failed) : ''));
        foreach ($failed as $t => $why) {
            $this->line("  <fg=yellow>{$t}</> — {$why}");
        }

        if ($this->option('write-seed')) {
            $this->writeSeed();
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }

    /** يبحث عن المقال المقابل: بالـslug أولاً ثم بالبحث النصّي. */
    private function lookup(Post $post): ?array
    {
        $slug = rawurldecode(trim(parse_url($post->source_url, PHP_URL_PATH) ?: '', '/'));
        $slug = trim(substr(strrchr('/'.$slug, '/'), 1));

        foreach ([
            ['slug' => $slug],
            ['search' => $post->title],
            ['search' => str_replace('-', ' ', $slug)],
        ] as $query) {
            $res = $this->get($query + ['per_page' => 3, '_fields' => 'slug,title,excerpt,content']);
            if (is_array($res) && ! empty($res)) {
                return $res[0];
            }
        }

        return null;
    }

    private function get(array $query): ?array
    {
        try {
            $http = Http::timeout(30)->retry(2, 800)->withHeaders(['User-Agent' => 'Mozilla/5.0']);
            if ($this->option('insecure')) {
                $http = $http->withoutVerifying();
            }
            $res = $http->get($this->api, $query);

            return $res->successful() ? $res->json() : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** يستخرج نصّاً آمناً بالترتيب: عناوين وفقرات وقوائم فقط. */
    private function extract(string $html, string $title): string
    {
        $html = preg_replace('#<(script|style|noscript|svg|form|iframe)\b[^>]*>.*?</\1>#is', '', $html);

        preg_match_all('#<(h2|h3|p|ul|ol)\b[^>]*>(.*?)</\1>#is', $html, $blocks, PREG_SET_ORDER);

        $out = [];
        $seen = [];
        $titleNorm = $this->norm($title);

        foreach ($blocks as $blk) {
            $tag = strtolower($blk[1]);

            if ($tag === 'ul' || $tag === 'ol') {
                preg_match_all('#<li\b[^>]*>(.*?)</li>#is', $blk[2], $lis);
                $items = [];
                foreach ($lis[1] as $li) {
                    $t = $this->clean($li);
                    if (mb_strlen($t) > 2 && ! $this->junk($t)) {
                        $items[] = '<li>'.e($t).'</li>';
                    }
                }
                if ($items) {
                    $out[] = '<ul>'.implode('', $items).'</ul>';
                }

                continue;
            }

            $t = $this->clean($blk[2]);
            if (mb_strlen($t) < ($tag === 'p' ? 25 : 6)) {
                continue;
            }
            if ($this->junk($t)) {
                continue;
            }
            // تخطّي العنوان المكرّر في بداية المقال
            if (str_starts_with($this->norm($t), $titleNorm) && count($out) < 2) {
                continue;
            }
            $key = mb_substr($this->norm($t), 0, 70);
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;

            $out[] = "<{$tag}>".e($t)."</{$tag}>";

            if (count($out) >= 40) {
                break;
            }
        }

        return implode("\n", $out);
    }

    private function clean(string $s): string
    {
        $s = preg_replace('#<br\s*/?>#i', ' ', $s);
        $s = strip_tags($s);
        $s = html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(preg_replace('/\s+/u', ' ', $s));
    }

    private function norm(string $s): string
    {
        return trim(preg_replace('/\s+/u', ' ', $s));
    }

    /** فقرات دعائية/تنقّلية لا تخصّ المقال. */
    private function junk(string $t): bool
    {
        return (bool) preg_match('/(اقرأ أيضا|واتساب|اتصل بنا الآن|جميع الحقوق|©|\b0[0-9]{8,}\b|9665[0-9]{7,})/u', $t);
    }

    private function writeSeed(): void
    {
        $path = database_path('seeders/data/posts.json');
        if (! is_file($path)) {
            return;
        }
        $rows = json_decode(file_get_contents($path), true);
        $byTitle = Post::pluck('body', 'title')->all();
        foreach ($rows as &$row) {
            if (! empty($byTitle[$row['title']])) {
                $row['body'] = $byTitle[$row['title']];
            }
        }
        unset($row);
        file_put_contents($path, json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $this->info('حُدّث ملف بذور المقالات.');
    }
}
