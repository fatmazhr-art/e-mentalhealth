<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ForumException;
use App\Http\Controllers\Controller;
use App\Models\KomunitasForum;
use App\Services\ForumService;
use App\Services\SanksiService;
use Illuminate\Http\Request;

class AdminForumController extends Controller
{
    public function __construct(
        protected ForumService $forumService,
        protected SanksiService $sanksiService
    ) {}

    /**
     * Tampilkan halaman moderasi Forum Anonim berbasis data database.
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'antrean');
        if (! in_array($tab, ['antrean', 'semua-konten', 'pengguna-dibatasi'], true)) {
            $tab = 'antrean';
        }

        $filters = [
            'search'       => $request->query('search'),
            'jenis'        => $request->query('jenis'),
            'id_komunitas' => $request->query('id_komunitas'),
            'status'       => $request->query('status'),
        ];

        $stats                = $this->forumService->getStatistikModerasi();
        $statsDibatasi        = $this->sanksiService->getStatistikDibatasi();
        $antreanLaporan       = $this->forumService->getAntreanLaporan();
        $semuaKontenPaginator = $this->forumService->getSemuaKonten($filters, 10);
        $semuaKonten          = $semuaKontenPaginator->items();
        $penggunaDibatasi     = $this->sanksiService->getPenggunaDibatasi();
        $komunitasList        = KomunitasForum::orderBy('id_komunitas')->get(['id_komunitas', 'nama']);

        return view('admin.forum.index', compact(
            'tab',
            'filters',
            'stats',
            'statsDibatasi',
            'antreanLaporan',
            'semuaKonten',
            'semuaKontenPaginator',
            'penggunaDibatasi',
            'komunitasList'
        ));
    }

    /**
     * Hapus konten forum (status menjadi 'dihapus').
     */
    public function hapusKonten(Request $request, int $id)
    {
        $validated = $request->validate([
            'alasan'      => 'nullable|string|max:100',
            'peringatkan' => 'nullable|boolean',
            'mute_24_jam' => 'nullable|boolean',
        ], [
            'alasan.string' => 'Alasan penghapusan harus berupa teks.',
            'alasan.max'    => 'Alasan penghapusan maksimal 100 karakter.',
        ]);

        $idAdmin = (int) $request->session()->get('admin_id');

        try {
            $this->forumService->hapusKonten(
                $id,
                $idAdmin,
                $validated['alasan'] ?? null,
                (bool) ($validated['peringatkan'] ?? false),
                (bool) ($validated['mute_24_jam'] ?? false)
            );
        } catch (ForumException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Konten berhasil dihapus dan dipindahkan ke arsip moderasi.');
    }

    /**
     * Pulihkan konten forum yang dihapus menjadi 'aktif'.
     */
    public function pulihkanKonten(int $id)
    {
        try {
            $this->forumService->pulihkanKonten($id);
        } catch (ForumException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Konten berhasil dipulihkan ke status aktif.');
    }

    /**
     * Tandai seluruh laporan menunggu pada konten terkait sebagai 'ditindak'.
     */
    public function tindakLaporan(Request $request, int $id)
    {
        $idAdmin = (int) $request->session()->get('admin_id');

        try {
            $this->forumService->tindakLaporan($id, $idAdmin);
        } catch (ForumException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Laporan berhasil ditindaklanjuti.');
    }

    /**
     * Tolak seluruh laporan menunggu pada konten terkait ('ditolak').
     */
    public function tolakLaporan(Request $request, int $id)
    {
        $idAdmin = (int) $request->session()->get('admin_id');

        try {
            $this->forumService->tolakLaporan($id, $idAdmin);
        } catch (ForumException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Laporan berhasil ditolak.');
    }

    /**
     * Berikan sanksi (peringatan, mute, atau blokir) kepada pengguna.
     */
    public function beriSanksi(Request $request)
    {
        $validated = $request->validate([
            'id_user'       => 'required|integer|exists:pengguna,id_user',
            'id_laporan'    => 'nullable|integer|exists:laporan_forum,id_laporan',
            'jenis'         => 'required|in:peringatan,mute,blokir',
            'alasan'        => 'required|string|max:100',
            'durasi_jam'    => 'nullable|integer|min:1|max:8760',
            'berakhir_pada' => 'nullable|date|after:now',
        ], [
            'id_user.required'    => 'Pengguna target wajib dipilih.',
            'id_user.exists'      => 'Pengguna target tidak ditemukan.',
            'id_laporan.exists'   => 'Data laporan tidak ditemukan.',
            'jenis.required'      => 'Jenis sanksi wajib dipilih.',
            'jenis.in'            => 'Jenis sanksi harus berupa peringatan, mute, atau blokir.',
            'alasan.required'     => 'Alasan sanksi wajib diisi.',
            'alasan.max'          => 'Alasan sanksi maksimal 100 karakter.',
            'durasi_jam.integer'  => 'Durasi harus berupa angka jam.',
            'durasi_jam.min'      => 'Durasi minimal 1 jam.',
            'berakhir_pada.date'  => 'Format tanggal berakhir sanksi tidak valid.',
            'berakhir_pada.after' => 'Waktu berakhir sanksi harus lebih dari waktu sekarang.',
        ]);

        $berakhirPada = $validated['berakhir_pada'] ?? null;
        if (! $berakhirPada && ! empty($validated['durasi_jam'])) {
            $berakhirPada = now()->addHours((int) $validated['durasi_jam']);
        }

        if ($validated['jenis'] === 'mute' && ! $berakhirPada) {
            return back()
                ->withInput()
                ->with('error', 'Batas waktu berakhir wajib diisi untuk sanksi mute.');
        }

        $idAdmin = (int) $request->session()->get('admin_id');

        try {
            $this->sanksiService->beriSanksi($idAdmin, [
                'id_user'       => (int) $validated['id_user'],
                'id_laporan'    => isset($validated['id_laporan']) ? (int) $validated['id_laporan'] : null,
                'jenis'         => $validated['jenis'],
                'alasan'        => $validated['alasan'],
                'berakhir_pada' => $berakhirPada,
            ]);
        } catch (ForumException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Sanksi berhasil diterapkan kepada pengguna.');
    }

    /**
     * Cabut sanksi pengguna yang masih aktif.
     */
    public function cabutSanksi(int $id)
    {
        try {
            $this->sanksiService->cabutSanksi($id);
        } catch (ForumException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Sanksi pengguna berhasil dicabut.');
    }
}
