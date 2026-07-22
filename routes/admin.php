<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'form'])->name('login');
        // بلا اسم: نفس المسار، والاسم محجوز لطلب الـGET أعلاه
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:10,1')->name('login.submit');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
        Route::get('leads/export', [LeadController::class, 'export'])->name('leads.export');
        Route::patch('leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
        Route::delete('leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('account', [AccountController::class, 'edit'])->name('account.edit');
        Route::put('account', [AccountController::class, 'update'])->name('account.update');

        // CRUD موحّد لكل موارد المحتوى
        Route::prefix('{resource}')->name('resource.')->group(function () {
            Route::get('/', [ResourceController::class, 'index'])->name('index');
            Route::post('reorder', [ResourceController::class, 'reorder'])->name('reorder');
            Route::get('create', [ResourceController::class, 'create'])->name('create');
            Route::post('/', [ResourceController::class, 'store'])->name('store');
            Route::get('{id}/edit', [ResourceController::class, 'edit'])->name('edit');
            Route::put('{id}', [ResourceController::class, 'update'])->name('update');
            Route::post('{id}/toggle', [ResourceController::class, 'toggle'])->name('toggle');
            Route::delete('{id}', [ResourceController::class, 'destroy'])->name('destroy');
        });
    });
});
