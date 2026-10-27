{{-- 
    Tabel Riwayat Asesmen DASS-21
    Sesuai Desain TARGET:
    - Dibungkus kartu: background #FAF6F3, border #D9D4D0, radius ~24px
    - Kolom: ID | NAMA/IDENTITAS | SKOR DEPRESI | SKOR KECEMASAN | SKOR STRES | STATUS
    - Header kapital, rata tengah, garis pemisah #333333 (1px)
    - Tinggi baris lega (~100-120px), konten rata tengah vertikal
    - Scroll vertikal (header sticky) dan scroll horizontal di dalam kartu
--}}
<div class="assesmen-table-card">
    <div class="assesmen-table-scroll-wrap">
        <table class="assesmen-target-table" role="table" aria-label="Daftar Riwayat Asesmen">
            <thead>
                <tr>
                    <th scope="col" class="th-id">ID</th>
                    <th scope="col" class="th-identitas">NAMA/IDENTITAS</th>
                    <th scope="col" class="th-skor">SKOR DEPRESI</th>
                    <th scope="col" class="th-skor">SKOR KECEMASAN</th>
                    <th scope="col" class="th-skor">SKOR STRES</th>
                    <th scope="col" class="th-status">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $item)
                    <tr>
                        <!-- ID (3 digit, nomor urut rata tengah) -->
                        <td class="td-id">
                            {{ $item['id'] }}
                        </td>

                        <!-- NAMA / IDENTITAS (Nama di atas, prodi di bawah teks kecil) -->
                        <td class="td-identitas">
                            <div class="user-identitas">
                                <span class="user-identitas__nama">{{ $item['nama'] }}</span>
                                <span class="user-identitas__prodi">{{ $item['prodi'] }}</span>
                            </div>
                        </td>

                        <!-- SKOR DEPRESI (Angka + Badge Kategori) -->
                        <td class="td-skor">
                            <div class="skor-item">
                                <span class="skor-item__angka">{{ $item['depresi'] }}</span>
                                <span class="skor-badge" style="background-color: {{ $item['badge_depresi']['bg'] }}; color: {{ $item['badge_depresi']['text'] }};">
                                    {{ $item['badge_depresi']['label'] }}
                                </span>
                            </div>
                        </td>

                        <!-- SKOR KECEMASAN (Angka + Badge Kategori) -->
                        <td class="td-skor">
                            <div class="skor-item">
                                <span class="skor-item__angka">{{ $item['kecemasan'] }}</span>
                                <span class="skor-badge" style="background-color: {{ $item['badge_kecemasan']['bg'] }}; color: {{ $item['badge_kecemasan']['text'] }};">
                                    {{ $item['badge_kecemasan']['label'] }}
                                </span>
                            </div>
                        </td>

                        <!-- SKOR STRES (Angka + Badge Kategori) -->
                        <td class="td-skor">
                            <div class="skor-item">
                                <span class="skor-item__angka">{{ $item['stres'] }}</span>
                                <span class="skor-badge" style="background-color: {{ $item['badge_stres']['bg'] }}; color: {{ $item['badge_stres']['text'] }};">
                                    {{ $item['badge_stres']['label'] }}
                                </span>
                            </div>
                        </td>

                        <!-- STATUS (Pill Abu #D9D9DB) -->
                        <td class="td-status">
                            <div class="status-pill">
                                {{ $item['status'] }}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="td-empty">
                            Belum ada riwayat asesmen
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
