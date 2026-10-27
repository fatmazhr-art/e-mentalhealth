<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminRehatController extends Controller
{

    public function index(Request $request)
    {
        $tab = $request->query('tab', 'artikel');

        if (! in_array($tab, ['artikel', 'audio', 'video'])) {
            $tab = 'artikel';
        }

        $dummyData = [
            'artikel' => [
                [
                    'judul'     => 'Megenal Sindrom Imposter Ditengah Tekanan Seminar...',
                    'deskripsi' => 'Panduan kognitif behavioral berbasis bukti untuk mengenali bias pemikiran saat menghadapi pertanyaan dosen penguji.',
                    'penulis'   => 'yayaya s.pd m.pd',
                    'baca'      => '5.000 baca',
                    'status'    => 'Dipublikasi',
                    'gambar'    => 'artikel-1.jpg',
                ],
                [
                    'judul'     => 'Navigasi Hari Pertama Magang: Menghadapi Social Fatigue',
                    'deskripsi' => 'Strategi praktis micro-break, regulasi stimulasi sosial, dan teknik grounding 5-4-3-2-1 untuk mengembalikan fokus.',
                    'penulis'   => 'yayaya s.pd m.pd',
                    'baca'      => '5.000 baca',
                    'status'    => 'Dipublikasi',
                    'gambar'    => 'artikel-2.jpg',
                ],
                [
                    'judul'     => "Ritual 15 Menit 'Brain Dump' Sebelum Tidur untuk...",
                    'deskripsi' => 'Menurunkan hiper-arousal otak saat deadline bertumpuk dengan protokol jurnalisme eksternalisasi beban pikiran.',
                    'penulis'   => 'yayaya s.pd m.pd',
                    'baca'      => '5.000 baca',
                    'status'    => 'Draf',
                    'gambar'    => 'artikel-3.jpg',
                ],
            ],
            'audio' => [
                [
                    'id'        => 'audio-1',
                    'judul'     => 'Hening Subuh di Kampus - Suara Hujan & Gemericik Air',
                    'deskripsi' => 'Rekaman high-definition atmosfer rintik hujan lembut di kanopi perpustakaan pusat, cocok untuk sesi belajar malam.',
                    'diputar'   => '2409 X Diputar',
                    'durasi'    => 'Durasi 35 min',
                    'durasi_detik' => 2100,
                    'status'    => 'Dipublikasi',
                    'warna'     => '#4F4F4F',

                    'media_url' => asset('media/audio/audio-1.mp3'),
                ],
                [
                    'id'        => 'audio-2',
                    'judul'     => 'Body Scan Meditation Sebelum Tidur',
                    'deskripsi' => 'Strategi praktis micro-break, regulasi stimulasi sosial, dan teknik grounding 5-4-3-2-1 untuk mengembalikan fokus.',
                    'diputar'   => '2409 X Diputar',
                    'durasi'    => 'Durasi 35 min',
                    'durasi_detik' => 2100,
                    'status'    => 'Dipublikasi',
                    'warna'     => '#D4505F',

                    'media_url' => asset('media/audio/audio-2.mp3'),
                ],
                [
                    'id'        => 'audio-3',
                    'judul'     => 'Gelombang Alpha 432Hz untuk Fokus Belajar & Tugas...',
                    'deskripsi' => 'Panduan relaksasi ketegangan otot progresif (PMR) mulai dari jari kaki hingga kepala guna mempercepat fase deep sleep.',
                    'diputar'   => '2409 X Diputar',
                    'durasi'    => 'Durasi 35 min',
                    'durasi_detik' => 2100,
                    'status'    => 'Draf',
                    'warna'     => '#00264D',

                    'media_url' => asset('media/audio/audio-3.mp3'),
                ],
            ],
            'video' => [
                [
                    'id'        => 'video-1',
                    'judul'     => 'Praktik Grounding 5-4-3-2-1 Saat Mengalami Panic',
                    'deskripsi' => 'Rekaman high-definition atmosfer rintik hujan lembut di kanopi perpustakaan pusat, cocok untuk sesi belajar malam.',
                    'diputar'   => '2409 X Diputar',
                    'durasi'    => 'Durasi 35 min',
                    'durasi_detik' => 2100,
                    'status'    => 'Dipublikasi',
                    'gambar'    => 'video-1.jpg',

                    'media_url' => asset('media/video/video-1.mp4'),
                ],
                [
                    'id'        => 'video-2',
                    'judul'     => 'Peregangan Otot Progresif (PMR) untuk Meredakan',
                    'deskripsi' => 'Strategi praktis micro-break, regulasi stimulasi sosial, dan teknik grounding 5-4-3-2-1 untuk mengembalikan fokus.',
                    'diputar'   => '2409 X Diputar',
                    'durasi'    => 'Durasi 35 min',
                    'durasi_detik' => 2100,
                    'status'    => 'Dipublikasi',
                    'gambar'    => 'video-2.jpg',

                    'media_url' => asset('media/video/video-2.mp4'),
                ],
                [
                    'id'        => 'video-3',
                    'judul'     => 'Psikoedukasi: Mengatasi Prokrastinasi Akademik &',
                    'deskripsi' => 'Panduan relaksasi ketegangan otot progresif (PMR) mulai dari jari kaki hingga kepala guna mempercepat fase deep sleep.',
                    'diputar'   => '2409 X Diputar',
                    'durasi'    => 'Durasi 35 min',
                    'durasi_detik' => 2100,
                    'status'    => 'Draf',
                    'gambar'    => 'video-3.jpg',

                    'media_url' => asset('media/video/video-3.mp4'),
                ],
            ],
            'pratinjau' => [
                'domain'    => 'google.com/ayosehat.kemkes.go.if',
                'judul'     => 'Megenal Sindrom Imposter Ditengah Tekanan Seminar...',
                'deskripsi' => 'Panduan kognitif behavioral berbasis bukti untuk mengenali bias pemikiran saat menghadapi pertanyaan dosen penguji.',
            ],
            'kategoriContoh' => ['Healing', 'tugas akhir', 'Kecemasan karir'],
            'formArtikel' => [
                'url'       => 'https://www.google search?q=mengatasi+burnout+akademik+skripsi',
                'judul'     => 'Mengenal sindrom imposter....',
                'deskripsi' => 'Panduan kognitif behavioral berbasis bukti untuk mengenali bias pemikiran saat menghadapi pertanyaan dosen penguji.',
                'kategori'  => ['Healing', 'tugas akhir', 'Kecemasan karir'],
                'pratinjau' => [
                    'domain'    => 'google.com/ayosehat.kemkes.go.if',
                    'judul'     => 'Megenal Sindrom Imposter Ditengah Tekanan Seminar...',
                    'deskripsi' => 'Panduan kognitif behavioral berbasis bukti untuk mengenali bias pemikiran saat menghadapi pertanyaan dosen penguji.',
                    'gambar'    => 'artikel-1.jpg',
                ],
            ],
            'formAudio' => [
                'judul'        => 'Please please please',
                'deskripsi'    => 'lagu nya enak relate sama etmin lagu nya enak relate sama etmin musik nya gaul pokoknua bagusmusik nya gaul pokoknua bagus lagu nya enak relate sama etmin musik nya gaul pokoknua bagus',
                'pengisiSuara' => 'Sabrina Carpenter',
                'durasi'       => '03.33',
            ],
            'formVideo' => [
                'url'       => 'https://www.google search?q=mengatasi+burnout+akademik+skripsi',
                'judul'     => '',
                'deskripsi' => '',
                'kategori'  => ['Healing', 'tugas akhir', 'Kecemasan karir'],
                'pratinjau' => [
                    'domain'    => 'google.com/ayosehat.kemkes.go.if',
                    'judul'     => 'Praktik Grounding 5-4-3-2-1 Saat Mengalami Panic',
                    'deskripsi' => 'Panduan kognitif behavioral berbasis bukti untuk mengenali bias pemikiran saat menghadapi pertanyaan dosen penguji.',
                    'gambar'    => 'video-1.jpg',
                ],
            ],
        ];

        return view('admin.rehat.index', array_merge(['tab' => $tab], $dummyData));
    }
}
