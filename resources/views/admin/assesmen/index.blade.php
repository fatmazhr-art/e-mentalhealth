{{--
    ======================================
    CURHATIN Admin — Assesmen DASS-21
    ======================================
    Halaman: /admin/assesmen
    CSS    : public/css/admin/assesmen.css

    DATA DARI CONTROLLER:
    - $tab            : tab aktif ('riwayat' | 'konfigurasi')
    - $totalResponden : jumlah seluruh hasil assesmen
    - $soalDASS       : paginator soal DASS-21
    - $jumlahDepresi, $jumlahCemas, $jumlahStres, $totalSoal
    - $riwayat        : paginator riwayat hasil assesmen
    ======================================
--}}
@extends('admin.layouts.base')

@section('title', 'Assesmen DASS-21 — CURHATIN')
@section('meta_description', 'Kelola soal asesmen DASS-21 dan pantau riwayat hasil assesmen mahasiswa')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav__item">
        <span class="material-icons-round">dashboard</span>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.assesmen.index') }}" class="sidebar-nav__item active">
        <span class="material-icons-round">assignment</span>
        <span>Asesmen DASS-21</span>
    </a>
    <a href="#" class="sidebar-nav__item">
        <span class="material-icons-round">coffee</span>
        <span>Rehat</span>
    </a>
    <a href="#" class="sidebar-nav__item">
        <span class="material-icons-round">groups</span>
        <span>Forum Anonim</span>
    </a>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/assesmen.css') }}">
@endpush

@section('content')
<div class="assesmen-wrapper">

    {{-- ===== HEADER ===== --}}
    <div class="assesmen-header">
        <div>
            <h1 class="assesmen-header__title">Assesmen Mandiri DASS-21</h1>
            <p class="assesmen-header__subtitle">
                Kelola soal asesmen dan soal penyebab yang tampil di aplikasi mahasiswa
            </p>
        </div>
        <button class="btn-tambah" id="btnTambahSoal">
            <span class="material-icons-round">add</span>
            Tambah Soal
        </button>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div style="background:#D1FAE5;border:1px solid #A7F3D0;color:#065F46;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-weight:500;font-size:13px;">
            <span style="vertical-align:middle" class="material-icons-round" style="font-size:16px">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- ===== KPI CARD ===== --}}
    <div class="kpi-single">
        <div class="kpi-single__label">Total Responden</div>
        <div class="kpi-single__value">
            @if($totalResponden > 1000000)
                {{ round($totalResponden / 1000000, 1) }} JUTA
            @elseif($totalResponden > 0)
                {{ number_format($totalResponden) }}
            @else
                19 JUTA
            @endif
        </div>
    </div>

    {{-- ===== TAB NAVIGATION ===== --}}
    <div class="tab-nav">
        <a href="{{ route('admin.assesmen.index', ['tab' => 'riwayat']) }}"
           class="tab-nav__item {{ $tab === 'riwayat' ? 'active' : '' }}">
            Daftar Riwayat Assesmen
        </a>
        <a href="{{ route('admin.assesmen.index', ['tab' => 'konfigurasi']) }}"
           class="tab-nav__item {{ $tab === 'konfigurasi' ? 'active' : '' }}">
            Konfigurasi Soal
        </a>
    </div>

    {{-- ===================================================
         TAB: KONFIGURASI SOAL
    =================================================== --}}
    @if($tab === 'konfigurasi')

        {{-- ---- Soal DASS-21 ---- --}}
        <div class="assesmen-card">
            <h2 class="assesmen-card__title">Soal DASS - 21</h2>
            <div class="soal-count">
                <span class="soal-count__total">{{ $totalSoal }} Soal</span>
                <span class="soal-count__sep">|</span>
                <span class="soal-count__item">{{ $jumlahDepresi }} Depresi</span>
                <span class="soal-count__sep">-</span>
                <span class="soal-count__item">{{ $jumlahCemas }} Kecemasan</span>
                <span class="soal-count__sep">-</span>
                <span class="soal-count__item">{{ $jumlahStres }} Stress</span>
            </div>
            <hr class="assesmen-card__divider">

            <div class="assesmen-table-wrap">
                <table class="assesmen-table">
                    <thead>
                        <tr>
                            <th class="col-no">NO</th>
                            <th class="col-soal">PERTANYAAN</th>
                            <th class="col-skala">SKALA</th>
                            <th class="col-status">STATUS</th>
                            <th class="col-aksi">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($soalDASS as $index => $soal)
                            <tr>
                                <td class="col-no">{{ $soalDASS->firstItem() + $index }}</td>
                                <td class="col-soal">{{ $soal->pertanyaan }}</td>
                                <td class="col-skala">
                                    @if($soal->kategori === 'stres')
                                        <span class="badge-skala badge-skala--stress">Stress</span>
                                    @elseif($soal->kategori === 'cemas')
                                        <span class="badge-skala badge-skala--cemas">Kecemasan</span>
                                    @else
                                        <span class="badge-skala badge-skala--depresi">Depresi</span>
                                    @endif
                                </td>
                                <td class="col-status">
                                    <span class="badge-aktif">Aktif</span>
                                </td>
                                <td class="col-aksi">
                                    <div style="display:flex;gap:6px;align-items:center;">
                                        <button class="btn-aksi btn-aksi--edit"
                                            onclick="openEditModal({{ $soal->id_soal }}, '{{ addslashes($soal->pertanyaan) }}', '{{ $soal->kategori }}', {{ $soal->bobot }})">
                                            EDIT
                                        </button>
                                        <form method="POST" action="{{ route('admin.assesmen.destroy', $soal->id_soal) }}"
                                              onsubmit="return confirm('Hapus soal ini?')" style="display:inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-aksi btn-aksi--hapus">HAPUS</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <span class="material-icons-round">quiz</span>
                                        <p class="empty-state__text">Belum ada soal DASS-21. Klik "+ Tambah Soal" untuk memulai.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Info --}}
            @if($soalDASS->total() > 0)
                <div class="pagination-info">
                    Menampilkan {{ $soalDASS->firstItem() }}–{{ $soalDASS->lastItem() }} dari {{ $soalDASS->total() }} soal
                    &nbsp;
                    {!! $soalDASS->appends(['tab' => 'konfigurasi'])->links('pagination::simple-default') !!}
                </div>
            @endif
        </div>

    {{-- ===================================================
         TAB: RIWAYAT ASSESMEN
    =================================================== --}}
    @else

        <div class="assesmen-card">
            <h2 class="assesmen-card__title">Riwayat Hasil Assesmen</h2>
            <p class="assesmen-card__subtitle">Seluruh hasil asesmen DASS-21 yang telah diselesaikan mahasiswa</p>
            <hr class="assesmen-card__divider">

            <div class="assesmen-table-wrap">
                <table class="assesmen-table">
                    <thead>
                        <tr>
                            <th class="col-no">NO</th>
                            <th class="col-nama">NAMA</th>
                            <th class="col-jurusan">JURUSAN</th>
                            <th class="col-tanggal">TANGGAL</th>
                            <th class="col-skor">STRESS</th>
                            <th class="col-skor">DEPRESI</th>
                            <th class="col-skor">CEMAS</th>
                            <th class="col-hasil">HASIL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $index => $item)
                            <tr>
                                <td class="col-no">{{ $riwayat->firstItem() + $index }}</td>
                                <td class="col-nama">
                                    {{ $item->nama_anonim ?? $item->nama ?? 'Anonim' }}
                                </td>
                                <td class="col-jurusan">{{ $item->jurusan ?? '-' }}</td>
                                <td class="col-tanggal">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </td>
                                <td class="col-skor" style="text-align:center">{{ $item->skor_stress }}</td>
                                <td class="col-skor" style="text-align:center">{{ $item->skor_depresi }}</td>
                                <td class="col-skor" style="text-align:center">{{ $item->skor_cemas }}</td>
                                <td class="col-hasil">
                                    @if($item->kategori_hasil === 'stres')
                                        <span class="badge-hasil--stress">Stress</span>
                                    @elseif($item->kategori_hasil === 'cemas')
                                        <span class="badge-hasil--cemas">Kecemasan</span>
                                    @else
                                        <span class="badge-hasil--depresi">Depresi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <span class="material-icons-round">history</span>
                                        <p class="empty-state__text">Belum ada riwayat assesmen.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($riwayat->total() > 0)
                <div class="pagination-info">
                    Menampilkan {{ $riwayat->firstItem() }}–{{ $riwayat->lastItem() }} dari {{ $riwayat->total() }} hasil
                    &nbsp;
                    {!! $riwayat->appends(['tab' => 'riwayat'])->links('pagination::simple-default') !!}
                </div>
            @endif
        </div>

    @endif

</div>

{{-- ===================================================
     MODAL: TAMBAH SOAL
=================================================== --}}
<div class="modal-overlay" id="modalTambah">
    <div class="modal-box">
        <button class="modal-close" id="modalTambahClose">
            <span class="material-icons-round">close</span>
        </button>
        <h3 class="modal-box__title" id="modalTitle">Tambah Soal DASS-21</h3>

        <form method="POST" id="formSoal" action="{{ route('admin.assesmen.store') }}">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="id_soal" id="formSoalId" value="">

            <div class="form-group">
                <label class="form-label" for="pertanyaan">Teks Pertanyaan</label>
                <textarea class="form-control" id="pertanyaan" name="pertanyaan"
                    rows="3" placeholder="Contoh: Saya merasa sulit untuk bersantai..." required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="kategori">Skala</label>
                    <select class="form-control" id="kategori" name="kategori" required>
                        <option value="">— Pilih Skala —</option>
                        <option value="stres">Stress</option>
                        <option value="cemas">Kecemasan</option>
                        <option value="depresi">Depresi</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="bobot">Bobot (1–5)</label>
                    <input type="number" class="form-control" id="bobot" name="bobot"
                        min="1" max="5" value="1" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">Simpan Soal</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ---- Modal open/close ----
    const modalTambah = document.getElementById('modalTambah');
    const btnTambah   = document.getElementById('btnTambahSoal');
    const btnClose    = document.getElementById('modalTambahClose');
    const formSoal    = document.getElementById('formSoal');
    const formMethod  = document.getElementById('formMethod');
    const formSoalId  = document.getElementById('formSoalId');
    const modalTitle  = document.getElementById('modalTitle');

    btnTambah.addEventListener('click', function () {
        // Reset ke mode tambah
        modalTitle.textContent    = 'Tambah Soal DASS-21';
        formSoal.action           = '{{ route('admin.assesmen.store') }}';
        formMethod.value          = 'POST';
        formSoalId.value          = '';
        document.getElementById('pertanyaan').value = '';
        document.getElementById('kategori').value   = '';
        document.getElementById('bobot').value      = '1';
        modalTambah.classList.add('show');
    });

    btnClose.addEventListener('click', () => modalTambah.classList.remove('show'));

    modalTambah.addEventListener('click', function (e) {
        if (e.target === this) this.classList.remove('show');
    });

    // ---- Edit mode ----
    function openEditModal(id, pertanyaan, kategori, bobot) {
        modalTitle.textContent  = 'Edit Soal DASS-21';
        formSoal.action         = '/admin/assesmen/' + id;
        formMethod.value        = 'PUT';
        formSoalId.value        = id;
        document.getElementById('pertanyaan').value = pertanyaan;
        document.getElementById('kategori').value   = kategori;
        document.getElementById('bobot').value      = bobot;
        modalTambah.classList.add('show');
    }
</script>
@endpush
