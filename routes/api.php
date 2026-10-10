<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SettingController;

Route::post('register', [AuthController::class, 'register'])->middleware('throttle:6,1');
Route::post('login', [AuthController::class, 'login'])->middleware('throttle:6,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
});

//public product api routes
Route::get('products',[ProductController::class,'index']);
Route::get('products/{slug}', [ProductController::class, 'show']);

//about and contact api endpoints
Route::get('about',[SettingController::class,'about']);
Route::get('contact',[SettingController::class,'contact']);
