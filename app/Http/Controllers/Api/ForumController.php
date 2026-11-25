<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ForumPostResource;
use App\Http\Resources\KomunitasForumResource;
use App\Http\Traits\ApiResponse;
use App\Models\ForumDiskusiAnonim;
use App\Models\KomunitasForum;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    use ApiResponse;

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
}
