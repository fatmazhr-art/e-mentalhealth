<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ForumException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ForumPostResource;
use App\Http\Resources\KomunitasForumResource;
use App\Http\Traits\ApiResponse;
use App\Models\ForumDiskusiAnonim;
use App\Models\KomunitasForum;
use App\Services\ForumService;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ForumService $forumService
    ) {}

    /**
     * Daftar seluruh komunitas forum yang tersedia.
     */
    public function communities()
    {
        $communities = KomunitasForum::orderBy('id_komunitas')->get();

        return $this->success(
            KomunitasForumResource::collection($communities),
            'Daftar komunitas forum berhasil diambil'
        );
    }

    /**
     * Daftar postingan utama forum yang berstatus aktif, terpaginasi dan urut terbaru.
     */
    public function posts(Request $request)
    {
        $validated = $request->validate([
            'id_komunitas' => 'nullable|integer',
            'per_page'     => 'nullable|integer|min:1|max:50',
        ], [
            'id_komunitas.integer' => 'ID komunitas harus berupa angka.',
            'per_page.integer'     => 'Jumlah data per halaman harus berupa angka.',
            'per_page.min'         => 'Jumlah data per halaman minimal 1.',
            'per_page.max'         => 'Jumlah data per halaman maksimal 50.',
        ]);

        $userId  = $request->user()->id_user;
        $perPage = $validated['per_page'] ?? 10;

        $posts = ForumDiskusiAnonim::query()
            ->with([
                'komunitas:id_komunitas,nama',
                'user:id_user,nama_anonim',
            ])
            ->withCount([
                'balasan as jumlah_balasan' => fn ($query) => $query->where('status', 'aktif'),
            ])
            ->withExists([
                'likes as sudah_like' => fn ($query) => $query->where('id_user', $userId),
            ])
            ->whereNull('id_induk')
            ->where('status', 'aktif')
            ->when(
                isset($validated['id_komunitas']),
                fn ($query) => $query->where('id_komunitas', $validated['id_komunitas'])
            )
            ->orderByDesc('tanggal_waktu')
            ->orderByDesc('id_post')
            ->paginate($perPage);

        return $this->success([
            'items'      => ForumPostResource::collection($posts->getCollection()),
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'last_page'    => $posts->lastPage(),
                'per_page'     => $posts->perPage(),
                'total'        => $posts->total(),
            ],
        ], 'Daftar postingan forum berhasil diambil');
    }

    /**
     * Buat postingan utama forum baru.
     */
    public function storePost(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:150',
            'isi_pesan'    => 'required|string',
            'kategori'     => 'required|string|max:50',
            'id_komunitas' => 'required|integer|exists:komunitas_forum,id_komunitas',
        ], [
            'judul.required'        => 'Judul postingan wajib diisi.',
            'judul.string'          => 'Judul postingan harus berupa teks.',
            'judul.max'             => 'Judul postingan maksimal 150 karakter.',
            'isi_pesan.required'    => 'Isi pesan wajib diisi.',
            'isi_pesan.string'      => 'Isi pesan harus berupa teks.',
            'kategori.required'     => 'Kategori wajib diisi.',
            'kategori.string'       => 'Kategori harus berupa teks.',
            'kategori.max'          => 'Kategori maksimal 50 karakter.',
            'id_komunitas.required' => 'Komunitas wajib dipilih.',
            'id_komunitas.integer'  => 'ID komunitas harus berupa angka.',
            'id_komunitas.exists'   => 'Komunitas yang dipilih tidak ditemukan.',
        ]);

        try {
            $post = $this->forumService->buatPost($request->user(), $validated);
        } catch (ForumException $e) {
            return $this->error($e->getMessage(), null, $e->getStatusCode());
        }

        return $this->success(
            new ForumPostResource($post),
            'Postingan forum berhasil dibuat',
            201
        );
    }

    /**
     * Buat balasan pada postingan utama forum yang aktif.
     */
    public function storeReply(Request $request, int $id)
    {
        $validated = $request->validate([
            'isi_pesan' => 'required|string',
        ], [
            'isi_pesan.required' => 'Isi balasan wajib diisi.',
            'isi_pesan.string'   => 'Isi balasan harus berupa teks.',
        ]);

        try {
            $balasan = $this->forumService->buatBalasan($request->user(), $id, $validated);
        } catch (ForumException $e) {
            return $this->error($e->getMessage(), null, $e->getStatusCode());
        }

        return $this->success(
            new ForumPostResource($balasan),
            'Balasan berhasil dikirim',
            201
        );
    }

    /**
     * Beri like pada postingan/balasan yang aktif.
     */
    public function like(Request $request, int $id)
    {
        try {
            $post = $this->forumService->likePost($request->user(), $id);
        } catch (ForumException $e) {
            return $this->error($e->getMessage(), null, $e->getStatusCode());
        }

        return $this->success(
            new ForumPostResource($post),
            'Postingan berhasil disukai'
        );
    }

    /**
     * Batalkan like pada postingan/balasan yang aktif.
     */
    public function unlike(Request $request, int $id)
    {
        try {
            $post = $this->forumService->unlikePost($request->user(), $id);
        } catch (ForumException $e) {
            return $this->error($e->getMessage(), null, $e->getStatusCode());
        }

        return $this->success(
            new ForumPostResource($post),
            'Like pada postingan berhasil dibatalkan'
        );
    }

    /**
     * Laporkan postingan/balasan yang melanggar aturan forum.
     */
    public function report(Request $request, int $id)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:ancaman,perundungan,spam,lainnya',
            'alasan'   => 'nullable|string|max:1000',
        ], [
            'kategori.required' => 'Kategori laporan wajib dipilih.',
            'kategori.in'       => 'Kategori laporan harus salah satu dari: ancaman, perundungan, spam, atau lainnya.',
            'alasan.string'     => 'Alasan laporan harus berupa teks.',
            'alasan.max'        => 'Alasan laporan maksimal 1000 karakter.',
        ]);

        try {
            $laporan = $this->forumService->laporkanPost($request->user(), $id, $validated);
        } catch (ForumException $e) {
            return $this->error($e->getMessage(), null, $e->getStatusCode());
        }

        return $this->success([
            'id_laporan'  => $laporan->id_laporan,
            'id_post'     => $laporan->id_post,
            'kategori'    => $laporan->kategori,
            'alasan'      => $laporan->alasan,
            'status'      => $laporan->status,
            'dibuat_pada' => $laporan->dibuat_pada,
        ], 'Laporan berhasil dikirim', 201);
    }
}
