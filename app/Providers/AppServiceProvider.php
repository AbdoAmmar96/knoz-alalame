<?php

namespace App\Providers;

use App\Models\Service;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // نفرض https اعتماداً على APP_URL نفسه لا على اسم البيئة:
        // فرضها في الإنتاج دون شهادة SSL يكسر روابط الصور وملفات التنسيق.
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // خدمات الفوتر تظهر في كل صفحة — تُحقن في القالب مباشرة
        // بدل تمريرها من كل كنترولر.
        View::composer('components.layouts.site', function ($view) {
            $view->with('footerServices', Service::live());
        });
    }
}
