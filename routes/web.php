<?php

use App\Http\Controllers\Analytics\StatsController;
use App\Http\Controllers\Analytics\TrackController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/api/track', [TrackController::class, 'track'])->name('analytics.track');

Route::get('/api/posts', [PostController::class, 'index'])->name('post.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/analytics', [StatsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/data', [StatsController::class, 'getData'])->name('analytics.data');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
