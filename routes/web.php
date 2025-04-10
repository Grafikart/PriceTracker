<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'doLogin']);

Route::middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\PricesController::class, 'index'])->name('prices.index');
    Route::get('/mail', [\App\Http\Controllers\UtilsController::class, 'mail']);
    Route::post('/', [\App\Http\Controllers\PricesController::class, 'store'])->name('prices.store');
});
