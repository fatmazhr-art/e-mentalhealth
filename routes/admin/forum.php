<?php

use App\Http\Controllers\Admin\AdminForumController;
use Illuminate\Support\Facades\Route;

// Modul F: moderasi forum anonim
Route::middleware('admin.auth')->group(function () {
    Route::get('/forum', [AdminForumController::class, 'index'])->name('admin.forum.index');
});
