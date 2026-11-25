<?php

use App\Http\Controllers\Api\ForumController;
use Illuminate\Support\Facades\Route;

// Modul F: Forum Diskusi Anonim (Tahap 1: endpoint baca)
Route::middleware('auth:sanctum')->prefix('forum')->group(function () {
    Route::get('/communities', [ForumController::class, 'communities']);
    Route::get('/posts', [ForumController::class, 'posts']);
});
