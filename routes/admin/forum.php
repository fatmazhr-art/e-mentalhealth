<?php

use App\Http\Controllers\Admin\AdminForumController;
use Illuminate\Support\Facades\Route;

// Modul F: moderasi forum anonim
Route::middleware('admin.auth')->group(function () {
    Route::get('/forum', [AdminForumController::class, 'index'])->name('admin.forum.index');

    Route::post('/forum/posts/{id}/hapus', [AdminForumController::class, 'hapusKonten'])
        ->whereNumber('id')
        ->name('admin.forum.posts.hapus');

    Route::post('/forum/posts/{id}/pulihkan', [AdminForumController::class, 'pulihkanKonten'])
        ->whereNumber('id')
        ->name('admin.forum.posts.pulihkan');

    Route::post('/forum/posts/{id}/tindak', [AdminForumController::class, 'tindakLaporan'])
        ->whereNumber('id')
        ->name('admin.forum.laporan.tindak');

    Route::post('/forum/posts/{id}/tolak', [AdminForumController::class, 'tolakLaporan'])
        ->whereNumber('id')
        ->name('admin.forum.laporan.tolak');

    Route::post('/forum/sanksi', [AdminForumController::class, 'beriSanksi'])
        ->name('admin.forum.sanksi.store');

    Route::post('/forum/sanksi/{id}/cabut', [AdminForumController::class, 'cabutSanksi'])
        ->whereNumber('id')
        ->name('admin.forum.sanksi.cabut');
});
