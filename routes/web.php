<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
| Route web admin dibagi per modul: setiap file di routes/admin/*.php dimuat
| otomatis dengan prefix /admin. Tambahkan route halaman adminmu di file
| miliknya sendiri, jangan di file ini. Tiap file mengatur sendiri
| middleware admin.auth / admin.guest.
*/
Route::prefix('admin')->group(function () {
    foreach (glob(__DIR__ . '/admin/*.php') as $file) {
        require $file;
    }
});
