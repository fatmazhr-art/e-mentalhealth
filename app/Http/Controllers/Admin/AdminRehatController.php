<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminRehatController extends Controller
{
    /**
     * Tampilkan halaman pengelolaan konten Rehat.
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'artikel');

        // Data konten Rehat sesuai screenshot desain
        $kontenList = [
            [
                'id'          => 1,
                'judul'       => 'Megenal Sindrom Imposter Ditengah Tekanan Seminar...',
                'deskripsi'   => 'Panduan kognitif behavioral berbasis bukti untuk mengenali bias pemikiran saat menghadapi pertanyaan dosen penguji.',
                'penulis'     => 'yayaya s.pd m.pd',
                'views'       => '5.000 baca',
                'gambar'      => 'images/rehat/rehat_imposter.jpg',
                'status'      => 'Dipublikasi',
                'kategori'    => 'artikel',
            ],
            [
                'id'          => 2,
                'judul'       => 'Navigasi Hari Pertama Magang: Menghadapi Social Fatigue',
                'deskripsi'   => 'Strategi praktis micro-break, regulasi stimulasi sosial, dan teknik grounding 5-4-3-2-1 untuk mengembalikan fokus.',
                'penulis'     => 'yayaya s.pd m.pd',
                'views'       => '5.000 baca',
                'gambar'      => 'images/rehat/rehat_magang.jpg',
                'status'      => 'Dipublikasi',
                'kategori'    => 'artikel',
            ],
            [
                'id'          => 3,
                'judul'       => "Ritual 15 Menit 'Brain Dump' Sebelum Tidur untuk...",
                'deskripsi'   => 'Menurunkan hiper-arousal otak saat deadline bertumpuk dengan protokol jurnalisme eksternalisasi beban pikiran.',
                'penulis'     => 'yayaya s.pd m.pd',
                'views'       => '5.000 baca',
                'gambar'      => 'images/rehat/rehat_braindump.jpg',
                'status'      => 'Draf',
                'kategori'    => 'artikel',
            ],
        ];

        return view('admin.rehat.index', compact('tab', 'kontenList'));
    }
}
