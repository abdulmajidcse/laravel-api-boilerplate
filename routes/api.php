<?php

use App\Http\Controllers\Auth\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ProfileController;


Route::prefix('auth')->group(function () {
    require __DIR__ . '/auth.php';

    Route::middleware(['auth:sanctum', 'verified'])->name('auth.')->group(function () {
        Route::get('/user', [ProfileController::class, 'user'])->name('user');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::apiResource('categories', CategoryController::class);
    });
});
