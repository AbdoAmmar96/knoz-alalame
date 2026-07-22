<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Project;
use App\Models\Sector;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * ينزّل كل الصور المرتبطة بروابط خارجية ويخزّنها محلياً.
 *
 * ضروري قبل الإطلاق: صور المحتوى مستضافة على konozcompany.com،
 * وهو الدومين نفسه الذي سيشير للموقع الجديد — فبمجرد التحويل
 * تصبح كل هذه الروابط 404 وتختفي صور الموقع بالكامل.
 */
class LocalizeImages extends Command
{
    protected $signature = 'konoz:localize-images
                            {--dry : عرض ما سيحدث دون تنزيل}
                            {--insecure : تخطّي التحقق من شهادة TLS}';

    protected $description = 'تنزيل صور المحتوى الخارجية وتخزينها محلياً';

    /** الحقول التي قد تحمل روابط صور. */
    private const MODELS = [Service::class, Sector::class, Project::class, Post::class];

    private const SETTING_KEYS = [
        'about_image', 'hero_image_about', 'hero_image_services',
        'hero_image_gallery', 'hero_image_blog', 'hero_image_contact',
    ];

    public function handle(): int
    {
        $dry = $this->option('dry');
        $map = [];       // رابط أصلي => مسار محلي (لتفادي تنزيل نفس الصورة مرتين)
        $failed = [];

        $download = function (string $url) use (&$map, &$failed, $dry): ?string {
            if (! str_starts_with($url, 'http')) {
                return null;                       // محلي أصلاً
            }
            if (isset($map[$url])) {
                return $map[$url];
            }

            $name = 'content/'.md5($url).'.'.(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg');

            if ($dry) {
                $this->line("  <fg=gray>سيُنزَّل</> {$url}");

                return $map[$url] = $name;
            }

            try {
                $http = Http::timeout(25)->retry(2, 500);
                // بعض بيئات التطوير على ويندوز بلا حزمة شهادات (curl.cainfo)
                // فيفشل التحقق. الخيار صريح ولا يُفعَّل تلقائياً.
                if ($this->option('insecure')) {
                    $http = $http->withoutVerifying();
                }
                $res = $http->get($url);
                if (! $res->successful() || strlen($res->body()) < 100) {
                    throw new \RuntimeException('HTTP '.$res->status());
                }
                Storage::disk('public')->put($name, $res->body());
                $this->line("  <fg=green>✔</> {$name}  <fg=gray>".number_format(strlen($res->body()) / 1024, 1)." KB</>");

                return $map[$url] = $name;
            } catch (\Throwable $e) {
                $failed[$url] = $e->getMessage();
                $this->line("  <fg=red>✘</> {$url} — {$e->getMessage()}");

                return null;
            }
        };

        foreach (self::MODELS as $model) {
            $rows = $model::whereNotNull('image')->where('image', 'like', 'http%')->get();
            if ($rows->isEmpty()) {
                continue;
            }
            $this->info(class_basename($model).' ('.$rows->count().')');
            foreach ($rows as $row) {
                if ($local = $download($row->image)) {
                    $dry || $row->update(['image' => $local]);
                }
            }
        }

        $this->info('الإعدادات');
        foreach (self::SETTING_KEYS as $key) {
            $value = Setting::get($key);
            if (is_string($value) && str_starts_with($value, 'http')) {
                if ($local = $download($value)) {
                    $dry || Setting::put($key, $local, 'heroes');
                }
            }
        }

        $this->newLine();
        $this->info('نُزّلت '.count($map).' صورة'.($failed ? ' — فشل '.count($failed) : ''));

        if ($failed) {
            $this->warn('روابط لم تُنزَّل (ما زالت خارجية):');
            foreach ($failed as $url => $why) {
                $this->line("  {$url} — {$why}");
            }

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
