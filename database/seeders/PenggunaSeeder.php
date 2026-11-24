<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public const PASSWORD_DEFAULT = 'password123';

    public function run(): void
    {
        // Hash sekali saja lalu dipakai ulang, supaya seeding tidak lambat.
        $sandi = Hash::make(self::PASSWORD_DEFAULT);

        // 1 admin
        Pengguna::create([
            'nama'       => 'Admin E-Mental Health',
            'role'       => 'admin',
            'email'      => 'admin@ementalhealth.test',
            'kata_sandi' => $sandi,
        ]);

        // 3 konselor
        $konselor = [
            ['Dr. Sari Wulandari, M.Psi.', 'sari'],
            ['Budi Santoso, M.Psi.',       'budi'],
            ['Rina Maharani, S.Psi.',      'rina'],
        ];
        foreach ($konselor as $i => [$nama, $slug]) {
            Pengguna::create([
                'nama'       => $nama,
                'role'       => 'konselor',
                'sip'        => sprintf('SIPP-2026-%03d', $i + 1),
                'no_hp'      => '08123450' . sprintf('%03d', $i + 1),
                'email'      => "konselor.{$slug}@ementalhealth.test",
                'kata_sandi' => $sandi,
            ]);
        }

        // Jurusan dan prodi contoh. Nama jurusan HARUS sama dengan yang dipakai
        // AdminDashboardController (whereIn jurusan), supaya statistik dashboard terisi.
        $jurusan = [
            ['Teknik Informatika', 'Teknologi Rekayasa Perangkat Lunak'],
            ['Manajemen Bisnis',   'Manajemen Bisnis Internasional'],
            ['Teknik Mesin',       'Teknologi Rekayasa Manufaktur'],
            ['Teknik Elektro',     'Teknik Listrik'],
        ];

        // 10 user biasa (mahasiswa), punya nama_anonim untuk forum
        $user = [
            ['Andi Pratama',     'Langit Senja'],
            ['Bunga Lestari',    'Kupu Malam'],
            ['Citra Dewi',       'Embun Pagi'],
            ['Dimas Saputra',    'Ombak Tenang'],
            ['Eka Putri',        'Bulan Sabit'],
            ['Fajar Nugroho',    'Angin Sepoi'],
            ['Gita Permata',     'Daun Jatuh'],
            ['Hendra Wijaya',    'Bintang Kecil'],
            ['Intan Maulida',    'Pelangi Sore'],
            ['Joko Susilo',      'Awan Putih'],
        ];
        foreach ($user as $i => [$nama, $anonim]) {
            $n = $i + 1;
            [$namaJurusan, $namaProdi] = $jurusan[$i % count($jurusan)];

            Pengguna::create([
                'nama'        => $nama,
                'nama_anonim' => $anonim,
                'role'        => 'user',
                'prodi'       => $namaProdi,
                'jurusan'     => $namaJurusan,
                'nim'         => sprintf('2024%06d', $n),
                'no_hp'       => '08567890' . sprintf('%03d', $n),
                'email'       => sprintf('user%02d@ementalhealth.test', $n),
                'kata_sandi'  => $sandi,
            ]);
        }
    }
}
