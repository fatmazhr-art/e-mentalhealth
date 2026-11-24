# E-Mental Health (Curhatin) - Backend

Satu project Laravel yang melayani dua hal sekaligus:

| Klien | Cara akses | Autentikasi |
|---|---|---|
| **Web admin** (Blade) | `routes/web.php`, alamat `/admin/...` | Session (`admin.auth`, `admin.guest`) |
| **Mobile** (dan klien lain) | `routes/api.php`, alamat `/api/v1/...` | Token Sanctum (`auth:sanctum`) |

Database satu: `db_curhatin`. Dua jalur login itu memakai tabel `pengguna` yang sama.

---

## 1. Setup (pertama kali)

Prasyarat: **Laragon** (PHP 8.2+, MySQL, Composer) dan Git. Pakai **terminal Laragon**, bukan PowerShell biasa.

```bash
git clone <url-repo>
cd e-mentalhealth
composer install
copy .env.example .env
php artisan key:generate
```

1. Buka Laragon, klik **Start All** (MySQL harus menyala).
2. Buat database kosong `db_curhatin` (HeidiSQL atau phpMyAdmin).
3. Cek `.env`: `DB_DATABASE=db_curhatin`, `DB_USERNAME=root`, `DB_PASSWORD=` (kosong).
4. Bangun tabel dan data contoh:

```bash
php artisan migrate:fresh --drop-views --seed
php artisan serve
```

Untuk fitur OTP (email di-queue), jalankan juga di terminal lain: `php artisan queue:work`.
Saat development pakai `MAIL_MAILER=log`, OTP dibaca di `storage/logs/laravel.log`.

### Masalah yang sering muncul di Windows

| Pesan | Penyebab dan solusi |
|---|---|
| `php is not recognized` | Pakai terminal Laragon, atau tambahkan folder PHP Laragon ke PATH lalu restart terminal |
| `Connection refused` / `[2002]` | MySQL belum menyala. Klik Start All di Laragon |
| `Access denied for user` | Isi `DB_USERNAME` dan `DB_PASSWORD` di `.env` sesuai MySQL lokalmu |
| `composer install` gagal "Could not delete" | Antivirus mengunci file. Kecualikan folder project dari antivirus, hapus folder `vendor`, ulangi |
| `Unknown database` | Database `db_curhatin` belum dibuat |
| `Trait ... not found` / class tidak ditemukan | Cek nama file dan namespace, lalu `composer dump-autoload` |

### Akun contoh (hasil seeder)

Kata sandi semua akun: `password123` (hanya untuk development).

| Role | Email |
|---|---|
| admin | `admin@ementalhealth.test` |
| konselor | `konselor.sari@ementalhealth.test`, `konselor.budi@...`, `konselor.rina@...` |
| user | `user01@ementalhealth.test` sampai `user10@ementalhealth.test` |

---

## 2. Aturan bersama (wajib dibaca)

### Database dan model
- Nama tabel **huruf kecil semua** (`pengguna`, `hasil_assesmen`). Server Linux membedakan huruf besar-kecil.
- Tabel tidak mengikuti konvensi Laravel, jadi **setiap model wajib** berisi:
  ```php
  protected $table = 'nama_tabel';
  protected $primaryKey = 'id_...';
  public $timestamps = false;
  ```
- Kolom password bernama `kata_sandi` (bukan `password`).
- Perubahan struktur tabel **selalu lewat migration baru**. Jangan edit migration yang sudah di-push dan jangan ubah database langsung.
- Nilai kategori, status, dan jenis ditulis **huruf kecil dengan garis bawah** (`tugas_akhir`, `sangat_berat`).

### Nilai tetap
| Kolom | Nilai |
|---|---|
| `pengguna.role` | `admin`, `user`, `konselor` |
| `soal.jenis` | `dass`, `penyebab` |
| `soal.kategori` | `stres`, `depresi`, `cemas` (kosong untuk soal penyebab) |
| `hasil_assesmen.kategori_penyebab` | `tugas_akhir`, `magang`, `keluarga`, `pertemanan`, `lainnya` |
| `sesi_konsultasi.status` | `menunggu`, `berlangsung`, `selesai`, `dibatalkan` |
| `rehat.jenis_konten` | `artikel`, `audio`, `video` |
| `rehat.status` | `draf`, `dipublikasi` |
| `forum_diskusi_anonim.status` | `aktif`, `berisiko`, `dihapus` |
| `laporan_forum.kategori` | `ancaman`, `perundungan`, `spam`, `lainnya` |
| `sanksi_pengguna.jenis` | `peringatan`, `mute`, `blokir` |
| `konsultasi_chatbot.pengirim` | `user`, `bot` |

### Format response API
Semua controller API memakai trait `App\Http\Traits\ApiResponse`.

```json
{ "success": true,  "message": "Berhasil", "data": { } }
{ "success": false, "message": "Email atau kata sandi salah", "errors": null }
```

```php
use App\Http\Traits\ApiResponse;

class ContohController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->success($data);
    }
}
```

Status HTTP: `200` berhasil, `201` dibuat, `401` belum login, `403` tidak punya hak akses, `422` validasi gagal.

### Hak akses (role)
Route API ditaruh di dalam grup `auth:sanctum`, lalu dibatasi dengan middleware `role`:

```php
Route::middleware('auth:sanctum')->group(function () {
    // semua yang sudah login
    Route::middleware('role:admin')->prefix('admin')->group(function () { /* hanya admin */ });
    Route::middleware('role:konselor,admin')->group(function () { /* konselor dan admin */ });
});
```

### Privasi (aplikasi kesehatan mental)
- Jangan pernah mengirim `id_user` atau nama asli di response forum anonim. Pakai `nama_anonim`.
- Jurnal mood tersimpan terenkripsi (`journal_cipher`). Jangan ubah `APP_KEY` setelah ada data.
- Konselor dan admin melihat hasil asesmen lewat `view_hasil_assesmen_ringkas` (tanpa nama asli).
- Jangan commit `.env`, dump database berisi data asli, atau token.

### Logika bersama
Logika inti (hitung skor asesmen, cek kuota jadwal) ditaruh di **satu class** di `app/Services`, dipanggil oleh web admin dan API. Jangan menulis logika yang sama di dua tempat.

---

## 3. Git

- Branch utama: `main` (stabil), `develop` (gabungan). **Jangan push langsung ke keduanya.**
- Tiap tugas satu branch dari `develop`: `feature/nama-modul`.
- Alur: buat branch, commit kecil-kecil, push, buka Pull Request ke `develop`, minimal satu orang me-review, baru merge.
- Sebelum mulai kerja: `git pull origin develop`.
- Pesan commit singkat dan jelas: `feat: endpoint login`, `fix: validasi kuota jadwal`.

```bash
git checkout develop
git pull origin develop
git checkout -b feature/nama-modul
```

---

## 4. Pembagian modul

| | Modul | Tabel | API (mobile) | Web admin |
|---|---|---|---|---|
| **A** | Auth, pengguna, fondasi | `pengguna` | register, login, OTP, reset sandi, profil, logout | Login admin, lupa sandi, profil, kelola pengguna |
| **B** | Asesmen dan dashboard | `soal`, `hasil_assesmen`, `detail_jawaban` | ambil soal, kirim jawaban (hitung skor), riwayat | Riwayat asesmen, konfigurasi soal, statistik dashboard |
| **C** | Mood dan Rehat | `moodtracker`, `perasaan`, `rehat`, `kategori_rehat`, `rehat_kategori` | daftar perasaan, catatan mood, daftar dan cari rehat | Kelola konten rehat (artikel, audio, video) |
| **D** | Jadwal, sesi, notifikasi | `jadwal_sesi`, `sesi_konsultasi`, `notifikasi` | lihat jadwal, pesan dan batalkan sesi, notifikasi | Kelola jadwal, pantau sesi |
| **E** | Chat dan chatbot | `pesan_konsultasi`, `konsultasi_chatbot` | chat dengan konselor, chatbot | Akses chat sesi yang ditangani |
| **F** | Forum dan moderasi | `forum_diskusi_anonim`, `forum_likes`, `komunitas_forum`, `laporan_forum`, `sanksi_pengguna` | postingan, balasan, like, laporkan | Antrean laporan, semua konten, sanksi pengguna |

Urutan ketergantungan: `pengguna` lebih dulu, lalu `jadwal_sesi` dan `sesi_konsultasi`, baru `pesan_konsultasi` dan `notifikasi`. D dan E perlu berkoordinasi.

---

## 5. Keputusan yang masih menunggu

- **Soal DASS-21 (21 butir) dan rumus skor** dari dosen belum diterima. Seeder memakai 9 soal contoh bertanda `SEMENTARA`. Selama itu dashboard menampilkan semua responden sebagai "normal".
- Sebagian halaman admin (dashboard per jurusan, forum, rehat) masih memakai data dummy dan harus diganti dengan query ke database oleh pemilik modulnya.
- Metode chatbot dan penanganan kondisi darurat belum diputuskan.
  
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


