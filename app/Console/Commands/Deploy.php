<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * خطوات ما بعد الرفع في أمر واحد + فحص جاهزية.
 */
class Deploy extends Command
{
    protected $signature = 'konoz:deploy {--check : فحص الجاهزية فقط دون تنفيذ}';

    protected $description = 'تجهيز الموقع بعد الرفع: هجرات، روابط، وكاش الإنتاج';

    public function handle(): int
    {
        if ($this->option('check')) {
            return $this->check();
        }

        $this->info('١. الهجرات');
        $this->call('migrate', ['--force' => true]);

        $this->info('٢. رابط مجلد الرفع');
        $this->call('storage:link');

        $this->info('٣. كاش الإنتاج');
        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('view:cache');
        $this->call('event:cache');

        $this->newLine();
        $this->info('تم. شغّل «php artisan konoz:deploy --check» للتأكد.');

        return self::SUCCESS;
    }

    private function check(): int
    {
        $rows = [];
        $fail = 0;

        $test = function (string $label, bool $ok, string $hint = '') use (&$rows, &$fail) {
            $rows[] = [$ok ? '✔' : '✘', $label, $ok ? '' : $hint];
            $ok || $fail++;
        };

        $test('APP_KEY مضبوط', (bool) config('app.key'), 'php artisan key:generate');
        $test('APP_DEBUG مُطفأ', config('app.debug') === false, 'اضبط APP_DEBUG=false');
        $test('APP_ENV = production', config('app.env') === 'production', 'اضبط APP_ENV=production');
        $test('APP_URL ليس localhost', ! str_contains((string) config('app.url'), 'localhost'),
            'اضبط APP_URL بدومين الموقع');
        $test('الاتصال بقاعدة البيانات', $this->dbOk(), 'راجع إعدادات DB_*');
        $test('جداول المحتوى موجودة', Schema::hasTable('services') && Schema::hasTable('settings'),
            'php artisan migrate --seed');
        // في النشر المقسوم يكون الرابط في جذر الويب لا داخل public المشروع،
        // فنقرأ موضعه من PUBLIC_DOCROOT إن كان مضبوطاً.
        // file_exists لا is_link: ويندوز ينشئ الرابط بنوع مختلف يجعل is_link تُرجع false.
        $docroot = rtrim((string) env('PUBLIC_DOCROOT', public_path()), '/');
        $test('رابط storage موجود', file_exists($docroot.'/storage'),
            'php artisan storage:link  (أو أنشئه يدوياً في جذر الويب)');
        $test('مجلد storage قابل للكتابة', is_writable(storage_path()), 'chmod -R 775 storage');
        $test('bootstrap/cache قابل للكتابة', is_writable(base_path('bootstrap/cache')),
            'chmod -R 775 bootstrap/cache');
        $test('public/robots.txt غير موجود', ! file_exists(public_path('robots.txt')),
            'احذفه — وجوده يحجب راوت robots الديناميكي');
        $test('كلمة مرور المدير ليست المنشورة', ! $this->defaultPasswordInUse(),
            'غيّرها فوراً من: اللوحة › حسابي');
        $test('الجلسة بكوكي آمن (HTTPS)', config('session.secure') === true,
            'اضبط SESSION_SECURE_COOKIE=true بعد تركيب SSL');
        $test('لا صور بروابط خارجية', $this->noExternalImages(),
            'php artisan konoz:localize-images');

        $this->newLine();
        $this->table(['', 'الفحص', 'الإجراء المطلوب'], $rows);

        if ($fail) {
            $this->warn("لم تكتمل {$fail} نقطة.");

            return self::FAILURE;
        }

        $this->info('جاهز للإطلاق.');

        return self::SUCCESS;
    }

    private function dbOk(): bool
    {
        try {
            \DB::connection()->getPdo();

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    /** كلمة المرور التي نُشرت سابقاً في التوثيق — يجب ألا تبقى مستخدمة. */
    private function defaultPasswordInUse(): bool
    {
        try {
            foreach (User::all() as $user) {
                if (Hash::check('konoz@2026', $user->password)) {
                    return true;
                }
            }

            return false;
        } catch (\Throwable) {
            return false;
        }
    }

    private function noExternalImages(): bool
    {
        try {
            foreach (['services', 'sectors', 'projects', 'posts'] as $table) {
                if (\DB::table($table)->where('image', 'like', 'http%')->exists()) {
                    return false;
                }
            }

            return true;
        } catch (\Throwable) {
            return true;
        }
    }
}
