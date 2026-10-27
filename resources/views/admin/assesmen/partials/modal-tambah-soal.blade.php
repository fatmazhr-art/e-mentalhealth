{{-- 
    Modal Tambah Soal — Asesmen Mandiri DASS-21
    Sesuai Desain TARGET:
    1. Jenis Soal: [Soal DASS - 21] vs [Soal Penyebab]
    2. Soal DASS-21:
       - Teks Pertanyaan
       - Skala (Depresi, Kecemasan, Stress)
       - Pilihan Jawaban tetap (0 - 3)
       - Nomor Urut + Aktifkan Soal
       - Warning alert: "Menambah soal DASS-21 mengubah perhitungan skor."
    3. Soal Penyebab:
       - Teks Pertanyaan
       - Tipe Jawaban: [Pilihan Ganda] vs [Skala 1 - 5]
       - Opsi Jawaban chips (+ Tambah Opsi, x untuk hapus)
       - Sub-kategori untuk opsi "Lainnya" (Pilihan Ganda)
       - Label Skala Angka 1 & Angka 5 + Lingkaran 1-5 (Skala 1 - 5)
       - Tampil untuk skala tertinggi (Depresi, Kecemasan, Stress)
       - Nomor Urut + Aktifkan Soal
       - Keterangan penentuan penyebab utama
    4. Tombol: [Batal] dan [Simpan Soal]
--}}

<div class="soal-modal-backdrop" id="modalTambahSoal" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="modalTambahSoalTitle" onclick="closeTambahSoalModal(event)">
    <div class="soal-modal-card" onclick="event.stopPropagation()">
        
        <!-- Header: Judul & Tombol Close X -->
        <div class="soal-modal__header">
            <h2 class="soal-modal__title" id="modalTambahSoalTitle">Tambah Soal</h2>
            <button type="button" class="soal-modal__close" onclick="closeTambahSoalModal()" aria-label="Tutup modal">
                &times;
            </button>
        </div>

        <form id="formTambahSoal" onsubmit="handleSimpanSoal(event)">
            @csrf

            <!-- 1. Pilihan Jenis Soal -->
            <div class="form-section">
                <label class="form-section__label">Jenis Soal</label>
                <div class="pill-group" role="radiogroup" aria-label="Jenis Soal">
                    <button type="button" 
                            class="pill-btn pill-btn--toggle is-active" 
                            id="btnJenisDass" 
                            onclick="setJenisSoal('dass')">
                        Soal DASS - 21
                    </button>
                    <button type="button" 
                            class="pill-btn pill-btn--toggle" 
                            id="btnJenisPenyebab" 
                            onclick="setJenisSoal('penyebab')">
                        Soal Penyebab
                    </button>
                </div>
                <input type="hidden" name="jenis_soal" id="inputJenisSoal" value="dass">
            </div>

            <!-- 2. Teks Pertanyaan -->
            <div class="form-section">
                <label for="teksPertanyaan" class="form-section__label">Teks Pertanyaan</label>
                <input type="text" 
                       id="teksPertanyaan" 
                       name="teks_pertanyaan" 
                       class="form-control-input" 
                       placeholder="Masukkan teks pertanyaan" 
                       value="Saya merasa sulit bersantai" 
                       required>
            </div>

            <!-- =======================================================
                 SECTION A: SOAL DASS - 21
                 ======================================================= -->
            <div id="sectionDass">
                <!-- Skala (Depresi | Kecemasan | Stress) -->
                <div class="form-section">
                    <label class="form-section__label">Skala</label>
                    <div class="pill-group" role="radiogroup" aria-label="Skala">
                        <button type="button" class="pill-btn pill-btn--toggle is-active" onclick="setSkalaDass(this, 'depresi')">Depresi</button>
                        <button type="button" class="pill-btn pill-btn--toggle" onclick="setSkalaDass(this, 'kecemasan')">Kecemasan</button>
                        <button type="button" class="pill-btn pill-btn--toggle" onclick="setSkalaDass(this, 'stress')">Stress</button>
                    </div>
                    <input type="hidden" name="skala_dass" id="inputSkalaDass" value="depresi">
                </div>

                <!-- Pilihan Jawaban (tetap untuk semua soal) -->
                <div class="form-section">
                    <label class="form-section__label">Pilihan Jawaban (tetap untuk semua soal)</label>
                    <div class="pill-group pill-group--static">
                        <span class="pill-tag pill-tag--static">0 Tidak Sesuai</span>
                        <span class="pill-tag pill-tag--static">1 Kadang Kadang</span>
                        <span class="pill-tag pill-tag--static">2 Lumayan Sering</span>
                        <span class="pill-tag pill-tag--static">3 Hampir Selalu</span>
                    </div>
                </div>

                <!-- Nomor Urut & Aktifkan Soal -->
                <div class="form-section">
                    <label for="nomorUrutDass" class="form-section__label">Nomor Urut</label>
                    <div class="form-inline-row">
                        <input type="number" 
                               id="nomorUrutDass" 
                               name="nomor_urut" 
                               class="form-control-number" 
                               value="22" 
                               min="1">
                        
                        <label class="checkbox-pill">
                            <input type="checkbox" name="aktif_dass" id="aktifDass" checked>
                            <span class="checkbox-pill__custom">
                                <span class="material-icons-round checkbox-pill__icon">check</span>
                            </span>
                            <span class="checkbox-pill__text">Aktifkan Soal</span>
                        </label>
                    </div>
                </div>

                <!-- Peringatan Kuning Perhitungan Skor -->
                <div class="soal-warning-box">
                    Menambah soal DASS-21 mengubah perhitungan skor.
                </div>
            </div>

            <!-- =======================================================
                 SECTION B: SOAL PENYEBAB
                 ======================================================= -->
            <div id="sectionPenyebab" style="display: none;">
                <!-- Tipe Jawaban: Pilihan Ganda vs Skala 1 - 5 -->
                <div class="form-section">
                    <label class="form-section__label">Tipe Jawaban</label>
                    <div class="pill-group" role="radiogroup" aria-label="Tipe Jawaban">
                        <button type="button" 
                                class="pill-btn pill-btn--toggle is-active" 
                                id="btnTipePilihanGanda" 
                                onclick="setTipeJawaban('pilihan_ganda')">
                            Pilihan Ganda
                        </button>
                        <button type="button" 
                                class="pill-btn pill-btn--toggle" 
                                id="btnTipeSkala" 
                                onclick="setTipeJawaban('skala')">
                            Skala 1 - 5
                        </button>
                    </div>
                    <input type="hidden" name="tipe_jawaban" id="inputTipeJawaban" value="pilihan_ganda">
                </div>

                <!-- Kategori / Opsi Jawaban (Pills yang dapat dihapus & ditambah) -->
                <div class="form-section">
                    <label class="form-section__label" id="labelOpsiJawaban">Opsi Jawaban</label>
                    <div class="tags-cloud" id="containerOpsiJawaban">
                        <span class="tag-chip">
                            <span>Tugas Akhir</span>
                            <button type="button" class="tag-chip__del" onclick="removeTagChip(this)">&times;</button>
                        </span>
                        <span class="tag-chip">
                            <span>Magang</span>
                            <button type="button" class="tag-chip__del" onclick="removeTagChip(this)">&times;</button>
                        </span>
                        <span class="tag-chip">
                            <span>Lainnya</span>
                            <button type="button" class="tag-chip__del" onclick="removeTagChip(this)">&times;</button>
                        </span>
                        <button type="button" class="btn-tambah-tag" onclick="tambahOpsiJawabanPrompt()">
                            + Tambah Opsi
                        </button>
                    </div>
                </div>

                <!-- SUB-KONTEN B1: Sub-kategori untuk opsi "Lainnya" (Hanya untuk Tipe Pilihan Ganda) -->
                <div class="sub-kategori-card" id="cardSubKategoriLainnya">
                    <label class="sub-kategori-card__label">Sub-kategori untuk opsi &ldquo;Lainnya&rdquo;</label>
                    
                    <div class="tags-cloud" id="containerSubKategori">
                        <span class="tag-chip">
                            <span>Keluarga</span>
                            <button type="button" class="tag-chip__del" onclick="removeTagChip(this)">&times;</button>
                        </span>
                        <span class="tag-chip">
                            <span>Keuangan</span>
                            <button type="button" class="tag-chip__del" onclick="removeTagChip(this)">&times;</button>
                        </span>
                        <span class="tag-chip">
                            <span>Hubungan</span>
                            <button type="button" class="tag-chip__del" onclick="removeTagChip(this)">&times;</button>
                        </span>
                        <span class="tag-chip">
                            <span>Kesehatan</span>
                            <button type="button" class="tag-chip__del" onclick="removeTagChip(this)">&times;</button>
                        </span>
                        <span class="tag-chip">
                            <span>Akademik</span>
                            <button type="button" class="tag-chip__del" onclick="removeTagChip(this)">&times;</button>
                        </span>
                        <span class="tag-chip">
                            <span>Masa depan dan karier</span>
                            <button type="button" class="tag-chip__del" onclick="removeTagChip(this)">&times;</button>
                        </span>
                        <button type="button" class="btn-tambah-tag" onclick="tambahSubKategoriPrompt()">
                            + Tambah Sub - Kategori
                        </button>
                    </div>

                    <!-- Checkbox Tampilkan isian singkat -->
                    <div style="margin-top: 14px;">
                        <label class="checkbox-pill">
                            <input type="checkbox" name="tampilkan_isian_singkat" id="checkIsianSingkat" checked>
                            <span class="checkbox-pill__custom">
                                <span class="material-icons-round checkbox-pill__icon">check</span>
                            </span>
                            <span class="checkbox-pill__text">Tampilkan isian singkat</span>
                        </label>
                    </div>

                    <!-- Batas Karakter & WAJIB DIISI -->
                    <div class="form-section" style="margin-top: 14px; margin-bottom: 0;">
                        <label class="form-section__label" style="font-size: 13px;">Batas Karakter</label>
                        <div class="form-inline-row">
                            <select name="batas_karakter" id="batasKarakter" class="form-control-select">
                                <option value="200" selected>200</option>
                                <option value="100">100</option>
                                <option value="300">300</option>
                                <option value="500">500</option>
                            </select>

                            <label class="checkbox-pill">
                                <input type="checkbox" name="wajib_diisi" id="checkWajibDiisi">
                                <span class="checkbox-pill__custom">
                                    <span class="material-icons-round checkbox-pill__icon">check</span>
                                </span>
                                <span class="checkbox-pill__text">WAJIB DIISI</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- SUB-KONTEN B2: Label Skala (Hanya untuk Tipe Skala 1 - 5) -->
                <div class="skala-labels-box" id="cardSkalaLabels" style="display: none;">
                    <label class="form-section__label">Label Skala</label>
                    <div class="skala-input-row">
                        <div class="skala-input-col">
                            <span class="skala-input-col__title">Angka 1</span>
                            <input type="text" class="form-control-input" name="label_skala_1" value="Tidak Sama Sekali">
                        </div>
                        <div class="skala-input-col">
                            <span class="skala-input-col__title">Angka 5</span>
                            <input type="text" class="form-control-input" name="label_skala_5" value="Sangat Besar">
                        </div>
                    </div>

                    <!-- Lingkaran 1 2 3 4 5 -->
                    <div class="skala-numbers-row">
                        <span class="skala-num-circle">1</span>
                        <span class="skala-num-circle">2</span>
                        <span class="skala-num-circle">3</span>
                        <span class="skala-num-circle">4</span>
                        <span class="skala-num-circle">5</span>
                    </div>
                </div>

                <!-- Tampil untuk skala tertinggi (Multi-select) -->
                <div class="form-section">
                    <label class="form-section__label">Tampil untuk skala tertinggi</label>
                    <div class="pill-group" role="group" aria-label="Skala Tertinggi">
                        <button type="button" class="pill-btn pill-btn--multi is-active" onclick="toggleMultiSkala(this)">Depresi</button>
                        <button type="button" class="pill-btn pill-btn--multi is-active" onclick="toggleMultiSkala(this)">Kecemasan</button>
                        <button type="button" class="pill-btn pill-btn--multi is-active" onclick="toggleMultiSkala(this)">Stress</button>
                    </div>
                </div>

                <!-- Nomor Urut & Aktifkan Soal (Penyebab) -->
                <div class="form-section">
                    <label for="nomorUrutPenyebab" class="form-section__label">Nomor Urut</label>
                    <div class="form-inline-row">
                        <input type="number" 
                               id="nomorUrutPenyebab" 
                               name="nomor_urut_penyebab" 
                               class="form-control-number" 
                               value="4" 
                               min="1">
                        
                        <label class="checkbox-pill">
                            <input type="checkbox" name="aktif_penyebab" id="aktifPenyebab" checked>
                            <span class="checkbox-pill__custom">
                                <span class="material-icons-round checkbox-pill__icon">check</span>
                            </span>
                            <span class="checkbox-pill__text">Aktifkan Soal</span>
                        </label>
                    </div>
                </div>

                <!-- Keterangan Soal Skala (Hanya untuk Tipe Skala 1 - 5) -->
                <p class="skala-note-text" id="noteSkalaPenyebab" style="display: none;">
                    Penyebab utama ditentukan dari kategori dengan rata-rata nilai tertinggi di semua soal skala.
                </p>
            </div>

            <!-- Footer Modal: Batal & Simpan Soal -->
            <div class="soal-modal__actions">
                <button type="button" class="btn-modal-cancel" onclick="closeTambahSoalModal()">
                    Batal
                </button>
                <button type="submit" class="btn-modal-submit">
                    Simpan Soal
                </button>
            </div>
        </form>

    </div>
</div>
