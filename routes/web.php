<?php

use App\Http\Controllers\FoodNetworkController;
use App\Http\Controllers\FoodProductController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

// Main food network page
Route::get('/', [FoodNetworkController::class, 'index'])->name('home');

// Food products routes (public viewing, auth required for creation)
Route::resource('food-products', FoodProductController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
Route::resource('reviews', ReviewController::class)->only(['index', 'show']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    
    // Protected food product routes
    Route::resource('food-products', FoodProductController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    
    // Protected review routes
    Route::resource('reviews', ReviewController::class)->except(['index', 'show']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
