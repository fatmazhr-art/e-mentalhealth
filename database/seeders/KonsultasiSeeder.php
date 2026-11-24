<?php

namespace Database\Seeders;

use App\Models\JadwalSesi;
use App\Models\Notifikasi;
use App\Models\Pengguna;
use App\Models\PesanKonsultasi;
use App\Models\SesiKonsultasi;
use Illuminate\Database\Seeder;

class KonsultasiSeeder extends Seeder
{
    public function run(): void
    {
        $konselor = Pengguna::where('role', 'konselor')->orderBy('id_user')->get();
        $users    = Pengguna::where('role', 'user')->orderBy('id_user')->get();

        $jadwal = JadwalSesi::orderBy('tanggal')->orderBy('jam_mulai')->get();

        $lampau   = $jadwal->where('tanggal', '<', now()->toDateString())->values(); // 3 hari lalu
        $hariIni  = $jadwal->where('tanggal', now()->toDateString())->values();
        $mendatang = $jadwal->where('tanggal', '>', now()->toDateString())->values();

        $sesi = [
            // [jadwal, konselor, user, status, catatan]
            [$lampau[0],     $konselor[0], $users[0], 'selesai',     'Klien merasa cemas menjelang ujian. Disarankan teknik pernapasan.'],
            [$lampau[1],     $konselor[1], $users[1], 'dibatalkan',  null],
            [$hariIni[0],    $konselor[0], $users[2], 'berlangsung', null],
            [$mendatang[0],  $konselor[2], $users[3], 'menunggu',    null],
        ];

        $dibuat = [];
        foreach ($sesi as [$j, $k, $u, $status, $catatan]) {
            $dibuat[$status] = SesiKonsultasi::create([
                'id_jadwal'   => $j->id_jadwal,
                'id_konselor' => $k->id_user,
                'id_user'     => $u->id_user,
                'catatan'     => $catatan,
                'status'      => $status,
            ]);
        }

        // Pesan hanya untuk sesi selesai & berlangsung
        $obrolan = [
            'selesai' => [
                ['user',     'Selamat pagi, Bu. Saya sering cemas kalau mau ujian.'],
                ['konselor', 'Selamat pagi. Terima kasih sudah datang. Sejak kapan perasaan itu muncul?'],
                ['user',     'Sekitar dua minggu terakhir.'],
                ['konselor', 'Baik, kita coba latihan napas dulu ya.'],
            ],
            'berlangsung' => [
                ['user',     'Halo, Pak. Saya mau cerita soal tidur yang berantakan.'],
                ['konselor', 'Halo. Silakan, ceritakan kebiasaan tidurmu belakangan ini.'],
            ],
        ];

        foreach ($obrolan as $status => $daftarPesan) {
            $s = $dibuat[$status];
            foreach ($daftarPesan as $menit => [$pengirim, $isi]) {
                PesanKonsultasi::create([
                    'id_sesi_konsultasi' => $s->id_sesi_konsultasi,
                    'id_pengirim'        => $pengirim === 'user' ? $s->id_user : $s->id_konselor,
                    'isi_pesan'          => $isi,
                    'is_read'            => $status === 'selesai',
                    'waktu'              => now()->subMinutes(30 - $menit),
                ]);
            }
        }

        // Notifikasi contoh
        Notifikasi::create([
            'id_user'            => $dibuat['menunggu']->id_user,
            'id_sesi_konsultasi' => $dibuat['menunggu']->id_sesi_konsultasi,
            'jenis'              => 'pengingat_sesi',
            'isi'                => 'Sesi konsultasimu akan dimulai besok. Jangan lupa hadir tepat waktu.',
            'sudah_dibaca'       => false,
        ]);
        Notifikasi::create([
            'id_user'            => $dibuat['berlangsung']->id_user,
            'id_sesi_konsultasi' => $dibuat['berlangsung']->id_sesi_konsultasi,
            'jenis'              => 'pesan_baru',
            'isi'                => 'Konselor mengirim pesan baru di sesi konsultasimu.',
            'sudah_dibaca'       => false,
        ]);
        Notifikasi::create([
            'id_user'            => $dibuat['selesai']->id_user,
            'id_sesi_konsultasi' => $dibuat['selesai']->id_sesi_konsultasi,
            'jenis'              => 'sesi_selesai',
            'isi'                => 'Sesi konsultasimu telah selesai. Terima kasih sudah berbagi cerita.',
            'sudah_dibaca'       => true,
            'waktu_baca'         => now()->subDays(2),
        ]);
    }
}
