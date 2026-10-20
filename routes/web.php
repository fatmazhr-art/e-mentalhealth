<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminForgotPasswordController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminAssesmenController;
use App\Http\Controllers\Admin\AdminRehatController;

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

    // Lupa Kata Sandi (OTP Flow)
    Route::get('/forgot-password', [AdminForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
    Route::post('/forgot-password', [AdminForgotPasswordController::class, 'sendOtp'])->name('admin.password.email');
    Route::get('/verify-otp', [AdminForgotPasswordController::class, 'showVerifyOtpForm'])->name('admin.password.verify');
    Route::post('/verify-otp', [AdminForgotPasswordController::class, 'verifyOtp'])->name('admin.password.verify.submit');
    Route::post('/forgot-password/resend-otp', [AdminForgotPasswordController::class, 'resendOtp'])->name('admin.password.resend');
    Route::get('/reset-password', [AdminForgotPasswordController::class, 'showResetForm'])->name('admin.password.reset');
    Route::post('/reset-password', [AdminForgotPasswordController::class, 'resetPassword'])->name('admin.password.update');
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

    // Rehat
    Route::get('/rehat',                 [AdminRehatController::class, 'index'])     ->name('admin.rehat.index');
});
