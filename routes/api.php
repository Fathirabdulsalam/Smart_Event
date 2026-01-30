<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaylabsCallbackController;

Route::prefix('paylabs/qris')->group(function () {
    Route::post('/create', [PaylabsCallbackController::class, 'qrisCreate']);
    Route::post('/query', [PaylabsCallbackController::class, 'qrisQuery']);
    Route::post('/paylabs/callback', [PaylabsCallbackController::class, 'handle'])->name('paylabs.notify');
});
