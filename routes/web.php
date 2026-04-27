<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return \Inertia\Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // Core Monitoring Routes
    Route::get('/orders/upload', [\App\Http\Controllers\OrderController::class, 'upload'])->name('orders.upload');
    Route::post('/orders/upload', [\App\Http\Controllers\OrderController::class, 'processUpload'])->name('orders.process-upload');
    Route::get('/orders/monitoring', [\App\Http\Controllers\OrderController::class, 'monitoring'])->name('orders.monitoring');
    Route::get('/orders/analytics', [\App\Http\Controllers\OrderController::class, 'analytics'])->name('orders.analytics');
    Route::resource('orders', \App\Http\Controllers\OrderController::class);
    
    // Management Routes
    Route::resource('mitras', \App\Http\Controllers\MitraController::class);
    Route::resource('regions', \App\Http\Controllers\RegionController::class);
    Route::resource('users', \App\Http\Controllers\UserController::class);

    // Configuration
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/photo', [ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');
});

require __DIR__.'/auth.php';
