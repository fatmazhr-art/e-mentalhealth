<?php

use Illuminate\Support\Facades\Route;

/*
| Route API dibagi per modul: setiap file di routes/api/*.php dimuat otomatis
| dengan prefix /api/v1. Tambahkan route modulmu di file miliknya sendiri,
| jangan di file ini. Tiap file mengatur sendiri middleware auth:sanctum / role.
*/
Route::prefix('v1')->group(function () {
    foreach (glob(__DIR__ . '/api/*.php') as $file) {
        require $file;
    }
});
