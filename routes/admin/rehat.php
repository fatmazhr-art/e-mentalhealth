<?php

use App\Http\Controllers\Admin\AdminRehatController;
use Illuminate\Support\Facades\Route;

// Modul C: konten rehat
Route::middleware('admin.auth')->group(function () {
    Route::get('/rehat', [AdminRehatController::class, 'index'])->name('admin.rehat.index');
});
