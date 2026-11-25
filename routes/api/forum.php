<?php

use App\Http\Controllers\Api\ForumController;
use Illuminate\Support\Facades\Route;

// Modul F: Forum Diskusi Anonim (Tahap 1 & 2)
Route::middleware('auth:sanctum')->prefix('forum')->group(function () {
    Route::get('/communities', [ForumController::class, 'communities']);
    Route::get('/posts', [ForumController::class, 'posts']);

    Route::post('/posts', [ForumController::class, 'storePost']);
    Route::post('/posts/{id}/replies', [ForumController::class, 'storeReply'])->whereNumber('id');
    Route::post('/posts/{id}/like', [ForumController::class, 'like'])->whereNumber('id');
    Route::delete('/posts/{id}/like', [ForumController::class, 'unlike'])->whereNumber('id');
    Route::post('/posts/{id}/report', [ForumController::class, 'report'])->whereNumber('id');
});
