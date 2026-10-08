<?php

use Illuminate\Support\Facades\Route;

Route::get('lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'ar']), 404);
    session(['locale' => $locale]);
    return back();
})->name('lang.switch');

Route::prefix('dashboard')->group(function () {
    Route::view('/', 'dashboard.home')->name('dashboard.home');
    Route::view('products', 'dashboard.products')->name('dashboard.products');
    Route::view('about', 'dashboard.about')->name('dashboard.about');
    Route::view('contact', 'dashboard.contact')->name('dashboard.contact');
});
