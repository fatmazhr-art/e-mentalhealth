<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminForgotPasswordController;
use App\Http\Controllers\Admin\AdminProfileController;
use Illuminate\Support\Facades\Route;

// Modul A: login, lupa kata sandi, profil admin

// Guest (belum login)
Route::middleware('admin.guest')->group(function () {
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

// Authenticated (sudah login)
Route::middleware('admin.auth')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Profil Admin
    Route::get('/profil', [AdminProfileController::class, 'index'])->name('admin.profile');
    Route::post('/profil', [AdminProfileController::class, 'updateProfile'])->name('admin.profile.update');
    Route::post('/profil/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.password');
    Route::post('/profil/foto', [AdminProfileController::class, 'updatePhoto'])->name('admin.profile.photo');
    Route::delete('/profil/foto', [AdminProfileController::class, 'deletePhoto'])->name('admin.profile.photo.delete');
});
