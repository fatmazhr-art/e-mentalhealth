{{-- 
    Tab Konfigurasi Soal — Asesmen Mandiri DASS-21
    Sesuai Desain TARGET:
    1. Card 1: Soal DASS - 21
       - 21 Soal  7 Depresi - 7 Kecemasan - 7 Stress
       - Garis pemisah merah maroon
       - Tabel: NO | PERTANYAAN | SKALA | STATUS | AKSI
       - Footer: Menampilkan 1–6 dari 21 soal
    2. Card 2: Soal Penyebab
       - Tampil setelah kuesioner untuk mengetahui sumber tekanan pada skala tertinggi
       - Garis pemisah merah maroon
       - Tabel: NO | PERTANYAAN | OPSI JAWABAN | TAMPIL UNTUK | STATUS | AKSI
--}}

<div class="konfigurasi-soal-wrapper">

    <!-- ========================================================
         KARTU 1: SOAL DASS - 21
         ======================================================== -->
    <div class="konfigurasi-card">
        <!-- Header Kartu -->
        <div class="konfigurasi-card__header">
            <h2 class="konfigurasi-card__title">Soal DASS - 21</h2>
            <div class="konfigurasi-card__subtitle">
                <span class="badge-total-soal">21 Soal</span>
                <span class="badge-kategori-split">7 Depresi - 7 Kecemasan - 7 Stress</span>
            </div>
        </div>

        <hr class="konfigurasi-card__divider">

        <!-- Tabel Soal DASS-21 -->
        <div class="konfigurasi-table-wrap">
            <table class="konfigurasi-table" role="table" aria-label="Tabel Soal DASS-21">
                <thead>
                    <tr>
                        <th scope="col" class="th-cfg-no">NO</th>
                        <th scope="col" class="th-cfg-soal">PERTANYAAN</th>
                        <th scope="col" class="th-cfg-skala">SKALA</th>
                        <th scope="col" class="th-cfg-status">STATUS</th>
                        <th scope="col" class="th-cfg-aksi">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($soalDass as $s)
                        <tr>
                            <!-- NO -->
                            <td class="td-cfg-no">{{ $s['no'] }}</td>

                            <!-- PERTANYAAN -->
                            <td class="td-cfg-soal">{{ $s['pertanyaan'] }}</td>

                            <!-- SKALA (Pill Badge) -->
                            <td class="td-cfg-skala">
                                <span class="badge-skala-dass" style="background-color: {{ $s['skala_bg'] }};">
                                    {{ $s['skala'] }}
                                </span>
                            </td>

                            <!-- STATUS (Pill Hijau Aktif) -->
                            <td class="td-cfg-status">
                                <span class="badge-status-aktif">{{ $s['status'] }}</span>
                            </td>

                            <!-- AKSI (EDIT & HAPUS) -->
                            <td class="td-cfg-aksi">
                                <div class="cfg-action-buttons">
                                    <button type="button" class="btn-cfg-edit" onclick="openEditSoalModal('{{ $s['no'] }}', '{{ addslashes($s['pertanyaan']) }}', '{{ $s['skala'] }}')">
                                        EDIT
                                    </button>
                                    <button type="button" class="btn-cfg-hapus" onclick="openHapusSoalModal('{{ $s['no'] }}', '{{ addslashes($s['pertanyaan']) }}', '{{ $s['skala'] }}')">
                                        HAPUS
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer Keterangan Jumlah Soal -->
        <div class="konfigurasi-card__footer">
            Menampilkan 1–6 dari 21 soal
        </div>
    </div>

    <!-- ========================================================
         KARTU 2: SOAL PENYEBAB
         ======================================================== -->
    <div class="konfigurasi-card" style="margin-top: 32px;">
        <!-- Header Kartu -->
        <div class="konfigurasi-card__header">
            <h2 class="konfigurasi-card__title">Soal Penyebab</h2>
            <div class="konfigurasi-card__subtitle-penyebab">
                Tampil setelah kuesioner untuk mengetahui sumber tekanan pada skala tertinggi
            </div>
        </div>

        <hr class="konfigurasi-card__divider">

        <!-- Tabel Soal Penyebab -->
        <div class="konfigurasi-table-wrap">
            <table class="konfigurasi-table" role="table" aria-label="Tabel Soal Penyebab">
                <thead>
                    <tr>
                        <th scope="col" class="th-cfg-no">NO</th>
                        <th scope="col" class="th-cfg-soal">PERTANYAAN</th>
                        <th scope="col" class="th-cfg-opsi">OPSI JAWABAN</th>
                        <th scope="col" class="th-cfg-tampil">TAMPIL UNTUK</th>
                        <th scope="col" class="th-cfg-status">STATUS</th>
                        <th scope="col" class="th-cfg-aksi">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($soalPenyebab as $p)
                        <tr>
                            <!-- NO -->
                            <td class="td-cfg-no">{{ $p['no'] }}</td>

                            <!-- PERTANYAAN -->
                            <td class="td-cfg-soal">{{ $p['pertanyaan'] }}</td>

                            <!-- OPSI JAWABAN (Chips Krem) -->
                            <td class="td-cfg-opsi">
                                <div class="cfg-opsi-chips">
                                    @foreach($p['opsi_jawaban'] as $opsi)
                                        <span class="chip-opsi-penyebab">{{ $opsi }}</span>
                                    @endforeach
                                </div>
                            </td>

                            <!-- TAMPIL UNTUK -->
                            <td class="td-cfg-tampil">
                                <span class="text-tampil-untuk">{{ $p['tampil_untuk'] }}</span>
                            </td>

                            <!-- STATUS (Pill Hijau Aktif) -->
                            <td class="td-cfg-status">
                                <span class="badge-status-aktif">{{ $p['status'] }}</span>
                            </td>

                            <!-- AKSI (EDIT & HAPUS) -->
                            <td class="td-cfg-aksi">
                                <div class="cfg-action-buttons">
                                    <button type="button" class="btn-cfg-edit" onclick="openEditSoalModal('{{ $p['no'] }}', '{{ addslashes($p['pertanyaan']) }}', 'Penyebab')">
                                        EDIT
                                    </button>
                                    <button type="button" class="btn-cfg-hapus" onclick="openHapusSoalModal('{{ $p['no'] }}', '{{ addslashes($p['pertanyaan']) }}', 'Penyebab')">
                                        HAPUS
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Edit Soal (Sesuai Desain Target) -->
<div class="edit-soal-modal-backdrop" id="modalEditSoal" style="display: none;" onclick="closeEditSoalModal(event)">
    <div class="edit-soal-modal-card" onclick="event.stopPropagation()">
        <button type="button" class="edit-soal-modal__close" onclick="closeEditSoalModal()">&times;</button>
        <h2 class="edit-soal-modal__title">Edit Soal</h2>
        <div class="edit-soal-modal__subtitle" id="editModalSub">Soal DASS 21 nomor 1</div>

        <form onsubmit="handleSimpanEdit(event)" style="margin-top: 18px;">
            <div class="form-section">
                <label class="form-section__label" style="font-size: 18px; font-weight: 800;">Pertanyaan</label>
                <textarea id="editTeksPertanyaan" class="form-control-input" rows="3" style="resize: vertical;">Saya merasa sulit bersantai</textarea>
                <span class="edit-soal-helper">Pernyataan ini tampil di aplikasi mahasiswa.</span>
            </div>

            <div class="form-section" style="margin-top: 18px;">
                <label class="form-section__label" style="font-size: 18px; font-weight: 800;">Skala</label>
                <div class="pill-group" id="editSkalaGroup">
                    <button type="button" class="pill-btn is-active" onclick="setEditSkala(this)">Depresi</button>
                    <button type="button" class="pill-btn" onclick="setEditSkala(this)">Kecemasan</button>
                    <button type="button" class="pill-btn" onclick="setEditSkala(this)">Stress</button>
                </div>
            </div>

            <div class="edit-status-box">
                <div>
                    <span class="edit-status-label">Status</span>
                    <span class="edit-status-desc">Soal aktif akan muncul di asesmen.</span>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span class="badge-status-aktif">Aktif</span>
                    <label class="switch-toggle">
                        <input type="checkbox" checked id="editStatusToggle">
                        <span class="switch-slider"></span>
                    </label>
                </div>
            </div>

            <div class="soal-modal__actions" style="margin-top: 24px;">
                <button type="button" class="btn-modal-cancel" onclick="closeEditSoalModal()">Batal</button>
                <button type="submit" class="btn-modal-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus Soal (Sesuai Desain Target) -->
<div class="hapus-soal-modal-backdrop" id="modalHapusSoal" style="display: none;" onclick="closeHapusSoalModal(event)">
    <div class="hapus-soal-modal-card" onclick="event.stopPropagation()">
        <button type="button" class="edit-soal-modal__close" onclick="closeHapusSoalModal()">&times;</button>
        <div class="hapus-icon-wrap">
            <span class="material-icons-round hapus-icon">delete_outline</span>
        </div>
        <h2 class="hapus-soal-title">Hapus Soal Ini?</h2>
        <div class="hapus-soal-preview" id="hapusSoalPreviewText">
            Saya merasa sulit untuk bersantai
        </div>
        <p class="hapus-soal-desc">
            Soal akan dihapus permanen dan tidak bisa dikembalikan.<br>
            Jumlah soal pada skala <span id="hapusSkalaText">Stress</span> akan berkurang dari 7 menjadi 6.
        </p>
        <p class="hapus-soal-subdesc">
            Jika hanya ingin menyembunyikannya sementara, ubah status menjadi nonaktif lewat Edit.
        </p>
        <div class="soal-modal__actions" style="justify-content: center; gap: 20px; margin-top: 28px;">
            <button type="button" class="btn-modal-cancel" style="min-width: 140px;" onclick="closeHapusSoalModal()">Batal</button>
            <button type="button" class="btn-modal-submit" style="min-width: 140px; background-color: #80001E;" onclick="handleConfirmHapus()">Ya, Hapus</button>
        </div>
    </div>
</div>
