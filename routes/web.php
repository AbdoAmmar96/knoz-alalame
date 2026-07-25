<?php

use App\Http\Controllers\Site\LeadController;
use App\Http\Controllers\Site\SitemapController;
use App\Http\Controllers\Site\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| صفحات الموقع
|--------------------------------------------------------------------------
| الروابط مطابقة لخريطة الموقع في konoz-master-plan.md (القسم 5).
*/

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/about', [SiteController::class, 'about'])->name('about');

Route::get('/services', [SiteController::class, 'services'])->name('services');
Route::get('/services/{service}', [SiteController::class, 'service'])->name('services.show');

Route::get('/maintenance-contracts', [SiteController::class, 'contracts'])->name('contracts');
Route::get('/gallery', [SiteController::class, 'gallery'])->name('gallery');
Route::get('/testimonials', [SiteController::class, 'testimonials'])->name('testimonials');

Route::get('/blog', [SiteController::class, 'blog'])->name('blog.index');
Route::get('/blog/{post}', [SiteController::class, 'post'])->name('blog.show');

Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [LeadController::class, 'store'])->name('contact.store')->middleware('throttle:6,1');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

require __DIR__.'/admin.php';
require __DIR__.'/redirects.php';
