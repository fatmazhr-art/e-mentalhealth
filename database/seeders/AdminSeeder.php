<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun admin utama untuk pengujian
        DB::table('Pengguna')->updateOrInsert(
            ['email' => 'admin@gmail.com'],
            [
                'nama'        => 'admin@gmail.com',
                'nama_anonim' => null,
                'role'        => 'admin',
                'prodi'       => 'Pusat Layanan Konseling',
                'jurusan'     => 'Kesehatan Mental Kampus',
                'nim'         => null,
                'email'       => 'admin@gmail.com',
                'kata_sandi'  => Hash::make('admin123'),
                'dibuat_pada' => now(),
            ]
        );

        // 2. Akun cadangan sesuai dengan email pada tampilan mockup
        DB::table('Pengguna')->updateOrInsert(
            ['email' => 'bismillahhapuskopdes@gmail.com'],
            [
                'nama'        => 'Admin Kampus',
                'nama_anonim' => null,
                'role'        => 'admin',
                'prodi'       => 'Kemahasiswaan',
                'jurusan'     => 'Teknik Informatika',
                'nim'         => null,
                'email'       => 'bismillahhapuskopdes@gmail.com',
                'kata_sandi'  => Hash::make('AMinnnnn'),
                'dibuat_pada' => now(),
            ]
        );

        // Tambahkan juga beberapa data dummy pengguna dan asesmen agar dashboard menampilkan statistik
        DB::table('Pengguna')->updateOrInsert(
            ['email' => 'mhs.ti@curhatin.com'],
            [
                'nama'        => 'Budi Santoso',
                'nama_anonim' => 'Bintang Malam',
                'role'        => 'user',
                'prodi'       => 'S1 Informatika',
                'jurusan'     => 'Teknik Informatika',
                'nim'         => '20240101',
                'kata_sandi'  => Hash::make('password123'),
                'dibuat_pada' => now(),
            ]
        );

        DB::table('Pengguna')->updateOrInsert(
            ['email' => 'mhs.mb@curhatin.com'],
            [
                'nama'        => 'Siti Rahma',
                'nama_anonim' => 'Awan Kelabu',
                'role'        => 'user',
                'prodi'       => 'S1 Manajemen',
                'jurusan'     => 'Manajemen Bisnis',
                'nim'         => '20240102',
                'kata_sandi'  => Hash::make('password123'),
                'dibuat_pada' => now(),
            ]
        );

        DB::table('Pengguna')->updateOrInsert(
            ['email' => 'mhs.tm@curhatin.com'],
            [
                'nama'        => 'Doni Pratama',
                'nama_anonim' => null,
                'role'        => 'user',
                'prodi'       => 'S1 Mesin',
                'jurusan'     => 'Teknik Mesin',
                'nim'         => '20240103',
                'kata_sandi'  => Hash::make('password123'),
                'dibuat_pada' => now(),
            ]
        );

        // Dummy data asesmen untuk grafik
        $userTI = DB::table('Pengguna')->where('email', 'mhs.ti@curhatin.com')->first();
        if ($userTI && DB::table('Hasil_Assesmen')->where('id_user', $userTI->id_user)->count() === 0) {
            DB::table('Hasil_Assesmen')->insert([
                'id_user'           => $userTI->id_user,
                'tanggal'           => now(),
                'kategori_hasil'    => 'stres',
                'skor_stress'       => 28,
                'skor_depresi'      => 18,
                'skor_cemas'        => 16,
                'kategori_penyebab' => 'tugas_akhir',
                'catatan_penyebab'  => 'Deadline skripsi dan bimbingan dosen',
            ]);
        }

        $userMB = DB::table('Pengguna')->where('email', 'mhs.mb@curhatin.com')->first();
        if ($userMB && DB::table('Hasil_Assesmen')->where('id_user', $userMB->id_user)->count() === 0) {
            DB::table('Hasil_Assesmen')->insert([
                'id_user'           => $userMB->id_user,
                'tanggal'           => now(),
                'kategori_hasil'    => 'cemas',
                'skor_stress'       => 16,
                'skor_depresi'      => 12,
                'skor_cemas'        => 18,
                'kategori_penyebab' => 'magang',
                'catatan_penyebab'  => 'Beban shift dan penugasan stase',
            ]);
        }
    }
}
