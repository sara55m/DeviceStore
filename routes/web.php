<?php

use App\Http\Controllers\Dashboard\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\SettingController;

Route::get('/', fn () => view('welcome'));

Route::get('lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'ar']), 404);
    session(['locale' => $locale]);
    return back();
})->name('lang.switch');

Route::middleware(['auth', 'admin'])->prefix('dashboard')->group(function () {
    Route::view('/', 'dashboard.home')->name('dashboard');

    Route::resource('products', ProductController::class)->except('show')->names('dashboard.products');

    Route::get('about', [SettingController::class, 'about'])->name('dashboard.about');
    Route::put('about', [SettingController::class, 'updateAbout'])->name('dashboard.about.update');
    Route::get('contact', [SettingController::class, 'contact'])->name('dashboard.contact');
    Route::put('contact', [SettingController::class, 'updateContact'])->name('dashboard.contact.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
