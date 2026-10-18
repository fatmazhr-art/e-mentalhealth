<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminAssesmenController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Rute-rute untuk panel admin, dipisahkan antara guest (belum login)
| dan authenticated (sudah login).
|
*/

// Admin - Guest Routes (belum login)
Route::prefix('admin')->middleware('admin.guest')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
});

// Admin - Authenticated Routes (sudah login)
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Assesmen DASS-21
    Route::get('/assesmen',              [AdminAssesmenController::class, 'index'])  ->name('admin.assesmen.index');
    Route::post('/assesmen',             [AdminAssesmenController::class, 'store'])  ->name('admin.assesmen.store');
    Route::get('/assesmen/create',       [AdminAssesmenController::class, 'create']) ->name('admin.assesmen.create');
    Route::get('/assesmen/{id}/edit',    [AdminAssesmenController::class, 'edit'])   ->name('admin.assesmen.edit');
    Route::put('/assesmen/{id}',         [AdminAssesmenController::class, 'update']) ->name('admin.assesmen.update');
    Route::delete('/assesmen/{id}',      [AdminAssesmenController::class, 'destroy'])->name('admin.assesmen.destroy');
});
