<?php

use App\Http\Controllers\Dashboard\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ProductController;

Route::get('/', fn () => view('welcome'));

Route::get('lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'ar']), 404);
    session(['locale' => $locale]);
    return back();
})->name('lang.switch');

Route::middleware(['auth', 'admin'])->prefix('dashboard')->group(function () {
    Route::view('/', 'dashboard.home')->name('dashboard');
    Route::resource('products', ProductController::class)->except('show')->names('dashboard.products');
    Route::view('about', 'dashboard.about')->name('dashboard.about');
    Route::view('contact', 'dashboard.contact')->name('dashboard.contact');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
