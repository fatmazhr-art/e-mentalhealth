<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Support\Facades\Route;

// Modul B: dashboard statistik
Route::middleware('admin.auth')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/{kategori}', [AdminDashboardController::class, 'detail'])->name('admin.dashboard.detail');
});
