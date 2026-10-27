/**
 * CURHATIN Admin — Assesmen DASS-21 Script
 * 
 * Menangani interaksi pada halaman Asesmen DASS-21:
 * - Modal Tambah Soal (Switch Jenis Soal DASS-21 vs Soal Penyebab)
 * - Switch Tipe Jawaban (Pilihan Ganda vs Skala 1 - 5)
 * - Interaktivitas Tag Chips (Hapus & Tambah dinamis)
 * 
 * KEAMANAN & PRIVASI:
 * // TODO: Halaman ini menampilkan data kesehatan mental mahasiswa yang bersifat rahasia.
 * // TODO: Akses wajib dibatasi hanya untuk admin atau psikolog resmi kampus yang berwenang.
 */

/* ==============================================================
   1. Modal Tambah Soal Open & Close
   ============================================================== */
function openTambahSoalModal() {
    const modal = document.getElementById('modalTambahSoal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('btnTambahSoalTarget');
    if (btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            openTambahSoalModal();
        });
    }
});

function closeTambahSoalModal(event) {
    if (event && event.target !== event.currentTarget) {
        return;
    }
    const modal = document.getElementById('modalTambahSoal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

/* ==============================================================
   2. Switcher Jenis Soal: DASS-21 vs Soal Penyebab
   ============================================================== */
function setJenisSoal(jenis) {
    const btnDass = document.getElementById('btnJenisDass');
    const btnPenyebab = document.getElementById('btnJenisPenyebab');
    const sectionDass = document.getElementById('sectionDass');
    const sectionPenyebab = document.getElementById('sectionPenyebab');
    const inputJenis = document.getElementById('inputJenisSoal');

    if (jenis === 'dass') {
        btnDass.classList.add('is-active');
        btnPenyebab.classList.remove('is-active');
        sectionDass.style.display = 'block';
        sectionPenyebab.style.display = 'none';
        if (inputJenis) inputJenis.value = 'dass';
    } else {
        btnPenyebab.classList.add('is-active');
        btnDass.classList.remove('is-active');
        sectionPenyebab.style.display = 'block';
        sectionDass.style.display = 'none';
        if (inputJenis) inputJenis.value = 'penyebab';
    }
}

/* ==============================================================
   3. Skala DASS-21 (Depresi | Kecemasan | Stress)
   ============================================================== */
function setSkalaDass(btn, skala) {
    const group = btn.closest('.pill-group');
    if (group) {
        group.querySelectorAll('.pill-btn').forEach(b => b.classList.remove('is-active'));
    }
    btn.classList.add('is-active');
    const input = document.getElementById('inputSkalaDass');
    if (input) input.value = skala;
}

/* ==============================================================
   4. Switcher Tipe Jawaban Soal Penyebab: Pilihan Ganda vs Skala 1 - 5
   ============================================================== */
function setTipeJawaban(tipe) {
    const btnPG = document.getElementById('btnTipePilihanGanda');
    const btnSkala = document.getElementById('btnTipeSkala');
    const cardSubKategori = document.getElementById('cardSubKategoriLainnya');
    const cardSkalaLabels = document.getElementById('cardSkalaLabels');
    const noteSkala = document.getElementById('noteSkalaPenyebab');
    const labelOpsi = document.getElementById('labelOpsiJawaban');
    const inputTipe = document.getElementById('inputTipeJawaban');

    if (tipe === 'pilihan_ganda') {
        btnPG.classList.add('is-active');
        btnSkala.classList.remove('is-active');
        cardSubKategori.style.display = 'block';
        cardSkalaLabels.style.display = 'none';
        noteSkala.style.display = 'none';
        if (labelOpsi) labelOpsi.textContent = 'Opsi Jawaban';
        if (inputTipe) inputTipe.value = 'pilihan_ganda';
    } else {
        btnSkala.classList.add('is-active');
        btnPG.classList.remove('is-active');
        cardSubKategori.style.display = 'none';
        cardSkalaLabels.style.display = 'block';
        noteSkala.style.display = 'block';
        if (labelOpsi) labelOpsi.textContent = 'Kategori Penyebab';
        if (inputTipe) inputTipe.value = 'skala';
    }
}

/* ==============================================================
   5. Multi-Select Skala Tertinggi (Depresi, Kecemasan, Stress)
   ============================================================== */
function toggleMultiSkala(btn) {
    btn.classList.toggle('is-active');
}

/* ==============================================================
   6. Interaktivitas Tag Chips (Opsi Jawaban & Sub-kategori)
   ============================================================== */
function removeTagChip(delBtn) {
    const chip = delBtn.closest('.tag-chip');
    if (chip) {
        chip.remove();
    }
}

function createTagChipElement(text) {
    const chip = document.createElement('span');
    chip.className = 'tag-chip';
    chip.innerHTML = `<span>${text}</span><button type="button" class="tag-chip__del" onclick="removeTagChip(this)">&times;</button>`;
    return chip;
}

function tambahOpsiJawabanPrompt() {
    const text = prompt('Masukkan nama opsi / kategori baru:');
    if (text && text.trim()) {
        const container = document.getElementById('containerOpsiJawaban');
        const addBtn = container.querySelector('.btn-tambah-tag');
        const newChip = createTagChipElement(text.trim());
        container.insertBefore(newChip, addBtn);
    }
}

function tambahSubKategoriPrompt() {
    const text = prompt('Masukkan nama sub-kategori baru:');
    if (text && text.trim()) {
        const container = document.getElementById('containerSubKategori');
        const addBtn = container.querySelector('.btn-tambah-tag');
        const newChip = createTagChipElement(text.trim());
        container.insertBefore(newChip, addBtn);
    }
}

/* ==============================================================
   7. Submit Handler Tambah Soal
   ============================================================== */
function handleSimpanSoal(event) {
    event.preventDefault();

    // TODO: Hubungkan form submit ini ke backend API / Controller untuk persistensi database tabel Soal
    alert('Soal baru berhasil disimpan (Simulasi Sukses).');
    closeTambahSoalModal();
}

/* ==============================================================
   8. Modal Edit Soal
   ============================================================== */
function openEditSoalModal(no, pertanyaan, skala) {
    const modal = document.getElementById('modalEditSoal');
    if (modal) {
        const sub = document.getElementById('editModalSub');
        if (sub) sub.textContent = `Soal DASS 21 nomor ${no}`;
        const inputTeks = document.getElementById('editTeksPertanyaan');
        if (inputTeks) inputTeks.value = pertanyaan;
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeEditSoalModal(event) {
    if (event && event.target !== event.currentTarget) {
        return;
    }
    const modal = document.getElementById('modalEditSoal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

function setEditSkala(btn) {
    const group = document.getElementById('editSkalaGroup');
    if (group) {
        group.querySelectorAll('.pill-btn').forEach(b => b.classList.remove('is-active'));
    }
    btn.classList.add('is-active');
}

function handleSimpanEdit(event) {
    event.preventDefault();
    alert('Perubahan soal berhasil disimpan.');
    closeEditSoalModal();
}

/* ==============================================================
   9. Modal Hapus Soal
   ============================================================== */
function openHapusSoalModal(no, pertanyaan, skala) {
    const modal = document.getElementById('modalHapusSoal');
    if (modal) {
        const preview = document.getElementById('hapusSoalPreviewText');
        if (preview) preview.textContent = pertanyaan;
        const skalaText = document.getElementById('hapusSkalaText');
        if (skalaText) skalaText.textContent = skala;

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeHapusSoalModal(event) {
    if (event && event.target !== event.currentTarget) {
        return;
    }
    const modal = document.getElementById('modalHapusSoal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

function handleConfirmHapus() {
    alert('Soal berhasil dihapus.');
    closeHapusSoalModal();
}

/* ==============================================================
   10. Global Keydown Listener (ESC untuk tutup semua modal)
   ============================================================== */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTambahSoalModal();
        closeEditSoalModal();
        closeHapusSoalModal();
    }
});

