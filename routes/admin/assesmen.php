<?php

use App\Http\Controllers\Admin\AdminAssesmenController;
use Illuminate\Support\Facades\Route;

// Modul B: asesmen DASS-21
Route::middleware('admin.auth')->group(function () {
    Route::get('/assesmen',           [AdminAssesmenController::class, 'index'])  ->name('admin.assesmen.index');
    Route::post('/assesmen',          [AdminAssesmenController::class, 'store'])  ->name('admin.assesmen.store');
    Route::get('/assesmen/create',    [AdminAssesmenController::class, 'create']) ->name('admin.assesmen.create');
    Route::get('/assesmen/{id}/edit', [AdminAssesmenController::class, 'edit'])   ->name('admin.assesmen.edit');
    Route::put('/assesmen/{id}',      [AdminAssesmenController::class, 'update']) ->name('admin.assesmen.update');
    Route::delete('/assesmen/{id}',   [AdminAssesmenController::class, 'destroy'])->name('admin.assesmen.destroy');
});
