<?php

use App\Http\Controllers\Api\Guest\GuestHomeApiController;
use App\Http\Controllers\Api\Guest\GuestOrderApiController;
use App\Http\Controllers\Api\Guest\GuestProductApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('guest')->name('api.guest.')->group(function () {
    Route::get('sync', [GuestHomeApiController::class, 'sync'])->name('sync');
    Route::get('home', [GuestHomeApiController::class, 'home'])->name('home');
    Route::get('products', [GuestProductApiController::class, 'index'])->name('products.index');
    Route::get('products/{product}', [GuestProductApiController::class, 'show'])->name('products.show');

    Route::post('orders', [GuestOrderApiController::class, 'store'])->name('orders.store');
});

