<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminForumController extends Controller
{
    /**
     * Tampilkan halaman moderasi Forum Anonim sesuai desain baru.
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'antrean');
        if (! in_array($tab, ['antrean', 'semua-konten', 'pengguna-dibatasi'])) {
            $tab = 'antrean';
        }

        // Statistik Moderasi
        $stats = [
            'laporan_baru'     => 7,
            'konten_beresiko'  => 1,
            'dihapus_hari_ini' => 4,
            'pengguna_dimute'  => 2,
        ];

        // Antrean Laporan
        $antreanLaporan = [
            [
                'id'            => 1,
                'jenis'         => 'Komentar',
                'kategori'      => 'Ancaman',
                'kategori_slug' => 'ancaman',
                'badge_color'   => '#7A0C2E',
                'waktu'         => '11.20',
                'penulis'       => 'Singa Malam',
                'komunitas'     => 'Magang',
                'isi'           => 'Liat aja nanti, aku cari tahu kamu siapa.',
                'jumlah_laporan'=> '4 laporan',
                'is_active'     => true,
                'post_konteks'  => [
                    'penulis'   => 'Anonim 345',
                    'waktu'     => '25 Menit yang lalu',
                    'komunitas' => 'Magang Dan Dunia Kerja',
                    'isi'       => 'Hari ketiga magang dan aku masih takut bertanya ke mentor. Rasanya semua orang sudah paham, cuma aku yang bingung. Ada yang pernah merasa begini juga?',
                ],
                'komentar_konteks' => [
                    'penulis'   => 'Anonim 123',
                    'waktu'     => '11.20',
                    'membalas'  => 'Anonim 345',
                    'isi'       => 'Liat aja nanti, aku cari tahu kamu siapa.',
                ],
                'riwayat'       => 'Riwayat akun Singa Malam: 1 peringatan, belum pernah di-mute. Identitas asli tidak ditampilkan kepada moderator.',
                'sub_laporan'   => 'Menunggu tindakan · 4 laporan (3 ancaman, 1 perundungan)',
            ],
            [
                'id'            => 2,
                'jenis'         => 'Komentar',
                'kategori'      => 'Perundungan',
                'kategori_slug' => 'perundungan',
                'badge_color'   => '#D97706',
                'waktu'         => '10.30',
                'penulis'       => 'Kopi Dingin',
                'komunitas'     => 'Magang',
                'isi'           => 'Gitu aja ngeluh, lemah banget.',
                'jumlah_laporan'=> '3 laporan',
                'is_active'     => false,
                'post_konteks'  => [
                    'penulis'   => 'Anonim 789',
                    'waktu'     => '1 jam lalu',
                    'komunitas' => 'Magang & Dunia Kerja',
                    'isi'       => 'Gimana cara bilang ke atasan kalau beban kerjaku terlalu banyak tapi takut dinilai gak kompeten ya?',
                ],
                'komentar_konteks' => [
                    'penulis'   => 'Kopi Dingin',
                    'waktu'     => '10.30',
                    'membalas'  => 'Anonim 789',
                    'isi'       => 'Gitu aja ngeluh, lemah banget.',
                ],
                'riwayat'       => 'Riwayat akun Kopi Dingin: Pernah dihapus 1x sebelumnya. Identitas asli tidak ditampilkan kepada moderator.',
                'sub_laporan'   => 'Menunggu tindakan · 3 laporan (3 perundungan)',
            ],
            [
                'id'            => 3,
                'jenis'         => 'Postingan',
                'kategori'      => 'Spam',
                'kategori_slug' => 'spam',
                'badge_color'   => '#475569',
                'waktu'         => '09.58',
                'penulis'       => 'Bulan Pagi',
                'komunitas'     => 'Self-Growth',
                'isi'           => 'Jual followers murah, hubungi aku.',
                'jumlah_laporan'=> '2 laporan',
                'is_active'     => false,
                'post_konteks'  => [
                    'penulis'   => 'Bulan Pagi',
                    'waktu'     => '09.58',
                    'komunitas' => 'Self-Growth',
                    'isi'       => 'Jual followers murah, hubungi aku di wa 0812345678.',
                ],
                'komentar_konteks' => null,
                'riwayat'       => 'Riwayat akun Bulan Pagi: Akun baru dibuat 2 hari lalu. Identitas asli tidak ditampilkan kepada moderator.',
                'sub_laporan'   => 'Menunggu tindakan · 2 laporan (2 spam)',
            ],
        ];

        // Semua Konten (Table)
        $semuaKonten = [
            [
                'id'        => 1,
                'jenis'     => 'Postingan',
                'isi'       => 'Hari ketiga magang dan aku masih takut bertanya ke mentor...',
                'isi_full'  => 'Hari ketiga magang dan aku masih takut bertanya ke mentor. Rasanya semua orang sudah paham, cuma aku yang bingung. Ada yang pernah merasa begini juga?',
                'penulis'   => 'Anonim 657',
                'alias'     => 'Kopi Hangat',
                'komunitas' => 'Magang & Dunia Kerja',
                'waktu'     => '25 menit lalu',
                'status'    => 'Aktif',
                'tags'      => ['Curhat', 'Magang'],
                'dukungan'  => 45,
                'komentar_count' => 16,
                'komentar_list' => [
                    [
                        'penulis' => 'Teh Hijau',
                        'waktu'   => '20 menit lalu',
                        'isi'     => 'Aku juga ngerasa gitu di minggu pertama. Pelan-pelan ya.',
                        'is_flagged' => false,
                    ],
                    [
                        'penulis' => 'Singa Malam',
                        'waktu'   => '11.20',
                        'isi'     => 'Liat aja nanti, aku cari tahu kamu siapa.',
                        'is_flagged' => true,
                        'laporan' => 4,
                    ],
                ],
                'aksi'      => ['lihat', 'hapus'],
            ],
            [
                'id'        => 2,
                'jenis'     => 'Postingan',
                'isi'       => 'Gimana cara bilang ke atasan kalau beban kerjaku terlalu banyak...',
                'isi_full'  => 'Gimana cara bilang ke atasan kalau beban kerjaku terlalu banyak tapi tetap terlihat profesional?',
                'penulis'   => 'Anonim 789',
                'alias'     => 'Anonim 789',
                'komunitas' => 'Magang & Dunia Kerja',
                'waktu'     => '1 jam lalu',
                'status'    => 'Aktif',
                'tags'      => ['Curhat', 'Magang'],
                'dukungan'  => 32,
                'komentar_count' => 8,
                'komentar_list' => [],
                'aksi'      => ['lihat', 'hapus'],
            ],
            [
                'id'        => 3,
                'jenis'     => 'Komentar',
                'isi'       => 'Liat aja nanti, aku cari tahu kamu siapa.',
                'isi_full'  => 'Liat aja nanti, aku cari tahu kamu siapa.',
                'penulis'   => 'Anonim 456',
                'alias'     => 'Anonim 456',
                'komunitas' => 'Magang & Dunia Kerja',
                'waktu'     => '11.20',
                'status'    => 'Dilaporkan 4',
                'tags'      => ['Ancaman'],
                'dukungan'  => 0,
                'komentar_count' => 0,
                'komentar_list' => [],
                'aksi'      => ['tinjau', 'hapus'],
            ],
            [
                'id'        => 4,
                'jenis'     => 'Postingan',
                'isi'       => 'Rasanya udah gak ada gunanya lagi...',
                'isi_full'  => 'Rasanya udah gak ada gunanya lagi berusaha keras kalau hasilnya selalu mengecewakan.',
                'penulis'   => 'Hujan Sore',
                'alias'     => 'Hujan Sore',
                'komunitas' => 'Self-Growth',
                'waktu'     => '10.45',
                'status'    => 'Berisiko',
                'tags'      => ['Berisiko', 'Curhat'],
                'dukungan'  => 12,
                'komentar_count' => 5,
                'komentar_list' => [],
                'aksi'      => ['tinjau'],
            ],
            [
                'id'        => 5,
                'jenis'     => 'Komentar',
                'isi'       => 'Gitu aja ngeluh, lemah banget.',
                'isi_full'  => 'Gitu aja ngeluh, lemah banget.',
                'penulis'   => 'Kopi Dingin',
                'alias'     => 'Kopi Dingin',
                'komunitas' => 'Magang & Dunia Kerja',
                'waktu'     => '10.30',
                'status'    => 'Dihapus',
                'is_deleted'=> true,
                'tags'      => ['Perundungan'],
                'dukungan'  => 1,
                'komentar_count' => 0,
                'komentar_list' => [],
                'aksi'      => ['pulihkan'],
            ],
            [
                'id'        => 6,
                'jenis'     => 'Postingan',
                'isi'       => 'Tips bikin kebiasaan nulis jurnal tiap malam sebelum tidur',
                'isi_full'  => 'Tips bikin kebiasaan nulis jurnal tiap malam sebelum tidur agar pikiran lebih tenang.',
                'penulis'   => 'Teh Hijau',
                'alias'     => 'Teh Hijau',
                'komunitas' => 'Self-Growth',
                'waktu'     => 'Kemarin',
                'status'    => 'Aktif',
                'tags'      => ['Tips', 'Self-Growth'],
                'dukungan'  => 56,
                'komentar_count' => 14,
                'komentar_list' => [],
                'aksi'      => ['lihat', 'hapus'],
            ],
        ];

        // Statistik Pengguna Dibatasi (Tab 3)
        $statsDibatasi = [
            'peringatan'  => 7,
            'mute_aktif'  => 2,
            'diblokir'    => 1,
        ];

        // Pengguna Dibatasi (Tab 3)
        $penggunaDibatasi = [
            [
                'id'        => 1,
                'nama'      => 'Singa Malam',
                'status'    => 'Mute 24 jam',
                'status_type' => 'mute',
                'alasan'    => 'Ancaman',
                'mulai'     => 'Hari ini 11.40',
                'berakhir'  => 'Besok 11.40',
                'sub_info'  => 'Alasan: Ancaman · sisa 18 jam · 1 peringatan sebelumnya',
                'aksi'      => ['Cabut', 'Perpanjang', 'Blokir'],
            ],
            [
                'id'        => 2,
                'nama'      => 'Kopi Dingin',
                'status'    => 'Peringatan 2',
                'status_type' => 'warning-2',
                'alasan'    => 'Perundungan',
                'mulai'     => 'Kemarin',
                'berakhir'  => '—',
                'sub_info'  => 'Alasan: Perundungan · 2 peringatan sebelumnya',
                'aksi'      => ['Cabut', 'Mute'],
            ],
            [
                'id'        => 3,
                'nama'      => 'Bulan Pagi',
                'status'    => 'Peringatan 1',
                'status_type' => 'warning-1',
                'alasan'    => 'Spam',
                'mulai'     => '2 hari lalu',
                'berakhir'  => '—',
                'sub_info'  => 'Alasan: Spam · 1 peringatan sebelumnya',
                'aksi'      => ['Cabut', 'Mute'],
            ],
            [
                'id'        => 4,
                'nama'      => 'Api Biru',
                'status'    => 'Diblokir 7 hari',
                'status_type' => 'blocked',
                'alasan'    => 'Ancaman berulang',
                'mulai'     => '5 hari lalu',
                'berakhir'  => '2 hari lagi',
                'sub_info'  => 'Alasan: Ancaman berulang · sisa 2 hari · 3 peringatan sebelumnya',
                'aksi'      => ['Cabut', 'Blokir permanen'],
            ],
        ];

        return view('admin.forum.index', compact('tab', 'stats', 'statsDibatasi', 'antreanLaporan', 'semuaKonten', 'penggunaDibatasi'));
    }
}

