@extends('admin.layouts.base')

@section('title', 'FORUM ANONIM — CURHATIN Admin')
@section('meta_description', 'Moderasi komunitas, postingan, dan komentar anonim')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav__item">
        <span class="material-icons-round">dashboard</span>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.assesmen.index') }}" class="sidebar-nav__item">
        <span class="material-icons-round">assignment</span>
        <span>Asesmen DASS-21</span>
    </a>
    <a href="{{ route('admin.rehat.index') }}" class="sidebar-nav__item">
        @include('admin.partials.icon-rehat')
        <span>Rehat</span>
    </a>
    <a href="{{ route('admin.forum.index') }}" class="sidebar-nav__item active">
        <span class="material-icons-round">groups</span>
        <span>Forum Anonim</span>
    </a>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/pages/forum.css') }}">
@endpush

@section('content')
<div class="forum-wrapper">

    <!-- Header Judul Besar FORUM ANONIM -->
    <div class="forum-header">
        <h1 class="forum-header__title">FORUM ANONIM</h1>
        <p class="forum-header__subtitle">
            Moderasi komunitas, postingan, dan komentar anonim
        </p>
    </div>

    <!-- Underline Navigation Tabs -->
    <div class="forum-nav-tabs-wrapper">
        <nav class="forum-nav-tabs" role="tablist">
            <a href="{{ route('admin.forum.index', ['tab' => 'antrean']) }}"
               class="forum-nav-tab {{ $tab === 'antrean' ? 'is-active' : '' }}"
               role="tab">
                <span>Antrean Laporan</span>
                <span class="forum-tab-badge">7</span>
            </a>
            <a href="{{ route('admin.forum.index', ['tab' => 'semua-konten']) }}"
               class="forum-nav-tab {{ $tab === 'semua-konten' ? 'is-active' : '' }}"
               role="tab">
                <span>Semua Konten</span>
            </a>
            <a href="{{ route('admin.forum.index', ['tab' => 'pengguna-dibatasi']) }}"
               class="forum-nav-tab {{ $tab === 'pengguna-dibatasi' ? 'is-active' : '' }}"
               role="tab">
                <span>Pengguna Dibatasi</span>
            </a>
        </nav>
    </div>

    @if($tab === 'antrean')
        <!-- 4 Stat Cards Row -->
        <div class="forum-stats-grid">
            <div class="forum-stat-card">
                <span class="forum-stat-card__label">Laporan Baru</span>
                <span class="forum-stat-card__value">{{ $stats['laporan_baru'] }}</span>
            </div>
            <div class="forum-stat-card">
                <span class="forum-stat-card__label">Konten Beresiko</span>
                <span class="forum-stat-card__value">{{ $stats['konten_beresiko'] }}</span>
            </div>
            <div class="forum-stat-card">
                <span class="forum-stat-card__label">Dihapus Hari Ini</span>
                <span class="forum-stat-card__value">{{ $stats['dihapus_hari_ini'] }}</span>
            </div>
            <div class="forum-stat-card">
                <span class="forum-stat-card__label">Pengguna Dimute</span>
                <span class="forum-stat-card__value">{{ $stats['pengguna_dimute'] }}</span>
            </div>
        </div>

        <!-- Split Layout Dua Kolom: Antrean di Kiri, Panel Moderasi di Kanan -->
        <div class="forum-antrean-layout">

            <!-- Kolom Kiri: Antrean Laporan -->
            <aside class="forum-queue-container">
                <!-- Tag Filter Chips -->
                <div class="forum-filter-chips">
                    <button type="button" class="forum-chip-btn is-active" data-filter="semua" onclick="filterQueue(this, 'semua')">Semua</button>
                    <button type="button" class="forum-chip-btn" data-filter="perundungan" onclick="filterQueue(this, 'perundungan')">Perundungan</button>
                    <button type="button" class="forum-chip-btn" data-filter="ancaman" onclick="filterQueue(this, 'ancaman')">Ancaman</button>
                    <button type="button" class="forum-chip-btn" data-filter="berisiko" onclick="filterQueue(this, 'berisiko')">Berisiko</button>
                    <button type="button" class="forum-chip-btn" data-filter="spam" onclick="filterQueue(this, 'spam')">Spam</button>
                </div>

                <!-- Daftar Kartu Laporan -->
                <div class="forum-queue-list" id="queueList">
                    @foreach($antreanLaporan as $index => $item)
                        <div class="forum-queue-card {{ $index === 0 ? 'is-selected' : '' }}"
                             data-id="{{ $item['id'] }}"
                             data-kategori="{{ $item['kategori_slug'] }}"
                             onclick="selectQueueById({{ $item['id'] }}, this)">
                            <div class="forum-queue-card__header">
                                <span class="forum-badge-type">{{ $item['jenis'] }}</span>
                                <span class="forum-badge-category" style="background-color: {{ $item['badge_color'] }};">
                                    {{ $item['kategori'] }}
                                </span>
                                <span class="forum-queue-card__time">{{ $item['waktu'] }}</span>
                            </div>
                            <h3 class="forum-queue-card__author">
                                {{ $item['penulis'] }} <span>· {{ $item['komunitas'] }}</span>
                            </h3>
                            <p class="forum-queue-card__snippet">{{ $item['isi'] }}</p>
                            <span class="forum-queue-card__footer">{{ $item['jumlah_laporan'] }}</span>
                        </div>
                    @endforeach
                </div>
            </aside>

            <!-- Kolom Kanan: Detail Moderasi -->
            <main class="forum-detail-panel" id="detailPanel">
                <h2 class="forum-detail-panel__title" id="detailTitle">Komentar Dilaporkan</h2>
                <p class="forum-detail-panel__subtitle" id="detailSubtitle">
                    Menunggu tindakan · 4 laporan (3 ancaman, 1 perundungan)
                </p>

                <div class="forum-detail-divider"></div>

                <h3 class="forum-detail-section-label" id="detailSectionLabel">Konteks Postingan</h3>

                <!-- Kartu Postingan Utama -->
                <div class="forum-context-post" id="detailPostBox">
                    <div class="forum-context-post__header">
                        <strong id="postAuthor">Anonim 345</strong>
                        <span id="postMeta">25 Menit yang lalu - Magang Dan Dunia Kerja</span>
                    </div>
                    <p class="forum-context-post__content" id="postContent">
                        Hari ketiga magang dan aku masih takut bertanya ke mentor. Rasanya semua orang sudah paham, cuma aku yang bingung. Ada yang pernah merasa begini juga?
                    </p>
                </div>

                <!-- Box Komentar Dilaporkan (Nested Merah) -->
                <div class="forum-context-comment" id="detailCommentBox">
                    <div class="forum-context-comment__header">
                        <strong id="commentAuthor">Anonim 123</strong>
                        <span id="commentMeta">11.20 Membalas Anonim 345</span>
                    </div>
                    <div class="forum-comment-quote-box">
                        <span id="commentContent">Liat aja nanti, aku cari tahu kamu siapa.</span>
                    </div>
                </div>

                <!-- Riwayat Catatan Moderasi -->
                <p class="forum-history-note" id="detailHistoryNote">
                    Riwayat akun Singa Malam: 1 peringatan, belum pernah di-mute. Identitas asli tidak ditampilkan kepada moderator.
                </p>

                <!-- Tombol Aksi Moderasi -->
                <div class="forum-detail-actions">
                    <button type="button" class="btn-mod-outline" onclick="abaikanLaporan()">Abaikan Laporan</button>
                    <button type="button" class="btn-mod-outline" onclick="muteUser(24)">Mute 24 Jam</button>
                    <button type="button" class="btn-mod-outline" onclick="peringatkanAkun()">Peringatkan Akun</button>
                    <button type="button" class="btn-mod-danger" id="btnHapusAction" onclick="openHapusKomentarModal()">HAPUS KOMENTAR</button>
                </div>
            </main>

        </div>

    @elseif($tab === 'semua-konten')
        <!-- Tab 2: Semua Konten Table -->
        <div class="forum-semua-konten-container">

            <!-- Filter Toolbar -->
            <div class="forum-filter-toolbar">
                <div class="forum-search-box">
                    <input type="text"
                           class="forum-search-box__input"
                           id="searchKontenInput"
                           placeholder="Cari isi konten atau nama samaran..."
                           oninput="filterKontenTable()">
                </div>
                <select class="forum-filter-select" id="filterJenis" onchange="filterKontenTable()">
                    <option value="">Semua jenis</option>
                    <option value="Postingan">Postingan</option>
                    <option value="Komentar">Komentar</option>
                </select>
                <select class="forum-filter-select" id="filterKomunitas" onchange="filterKontenTable()">
                    <option value="">Semua komunitas</option>
                    <option value="Magang & Dunia Kerja">Magang & Dunia Kerja</option>
                    <option value="Self-Growth">Self-Growth</option>
                </select>
                <select class="forum-filter-select" id="filterStatus" onchange="filterKontenTable()">
                    <option value="">Semua status</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Dilaporkan">Dilaporkan</option>
                    <option value="Berisiko">Berisiko</option>
                    <option value="Dihapus">Dihapus</option>
                </select>
            </div>

            <!-- Tabel Konten -->
            <div class="forum-table-responsive">
                <table class="forum-table" id="tableKonten">
                    <thead>
                        <tr>
                            <th>JENIS</th>
                            <th>ISI</th>
                            <th>NAMA SAMARAN</th>
                            <th>KOMUNITAS</th>
                            <th>WAKTU</th>
                            <th>STATUS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($semuaKonten as $row)
                            <tr class="forum-table-row" data-jenis="{{ $row['jenis'] }}" data-komunitas="{{ $row['komunitas'] }}" data-status="{{ $row['status'] }}">
                                <td>
                                    <span class="forum-badge-pill forum-badge-pill--type">{{ $row['jenis'] }}</span>
                                </td>
                                <td>
                                    @if(!empty($row['is_deleted']))
                                        <span class="text-strikethrough">{{ $row['isi'] }}</span>
                                    @else
                                        {{ $row['isi'] }}
                                    @endif
                                </td>
                                <td>
                                    <span class="forum-table-author">{{ $row['penulis'] }}</span>
                                </td>
                                <td>{{ $row['komunitas'] }}</td>
                                <td>{{ $row['waktu'] }}</td>
                                <td>
                                    @if(str_contains($row['status'], 'Aktif'))
                                        <span class="forum-badge-pill forum-badge-pill--aktif">{{ $row['status'] }}</span>
                                    @elseif(str_contains($row['status'], 'Dilaporkan'))
                                        <span class="forum-badge-pill forum-badge-pill--dilaporkan">{{ $row['status'] }}</span>
                                    @elseif(str_contains($row['status'], 'Berisiko'))
                                        <span class="forum-badge-pill forum-badge-pill--berisiko">{{ $row['status'] }}</span>
                                    @elseif(str_contains($row['status'], 'Dihapus'))
                                        <span class="forum-badge-pill forum-badge-pill--dihapus">{{ $row['status'] }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="forum-table-actions">
                                        @if(in_array('lihat', $row['aksi']))
                                            <button type="button" class="table-action-link" onclick="openDetailPostById({{ $row['id'] }})">Lihat</button>
                                        @endif
                                        @if(in_array('tinjau', $row['aksi']))
                                            <a href="{{ route('admin.forum.index', ['tab' => 'antrean']) }}" class="table-action-link">Tinjau</a>
                                        @endif
                                        @if(in_array('hapus', $row['aksi']))
                                            @if($row['jenis'] === 'Postingan')
                                                <button type="button" class="table-action-link" onclick="openHapusPostById({{ $row['id'] }})">Hapus</button>
                                            @else
                                                <button type="button" class="table-action-link" onclick="openHapusKomentarModalWithData('{{ $row['penulis'] }}', '{{ $row['waktu'] }}', '{{ addslashes($row['isi']) }}')">Hapus</button>
                                            @endif
                                        @endif
                                        @if(in_array('pulihkan', $row['aksi']))
                                            <button type="button" class="table-action-link" onclick="alert('Konten berhasil dipulihkan!')">Pulihkan</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="forum-pagination-footer">
                <span class="forum-pagination-info">Menampilkan 1-6 dari 1.284 konten</span>
                <div class="forum-pagination-btns">
                    <button type="button" class="forum-page-btn is-active">1</button>
                    <button type="button" class="forum-page-btn">2</button>
                    <button type="button" class="forum-page-btn">3</button>
                    <button type="button" class="forum-page-btn">›</button>
                </div>
            </div>

        </div>

    @elseif($tab === 'pengguna-dibatasi')
        <!-- Tab 3: 3 Stat Cards Row -->
        <div class="forum-stats-grid forum-stats-grid--3cols">
            <div class="forum-stat-card forum-stat-card--warm">
                <span class="forum-stat-card__label">Peringatan</span>
                <span class="forum-stat-card__value">{{ $statsDibatasi['peringatan'] }}</span>
            </div>
            <div class="forum-stat-card forum-stat-card--warm">
                <span class="forum-stat-card__label">Mute Aktif</span>
                <span class="forum-stat-card__value">{{ $statsDibatasi['mute_aktif'] }}</span>
            </div>
            <div class="forum-stat-card forum-stat-card--warm">
                <span class="forum-stat-card__label">Diblokir</span>
                <span class="forum-stat-card__value">{{ $statsDibatasi['diblokir'] }}</span>
            </div>
        </div>

        <!-- Tab 3: Container Tabel Pengguna Dibatasi -->
        <div class="forum-semua-konten-container">

            <!-- Filter Toolbar -->
            <div class="forum-filter-toolbar">
                <div class="forum-search-box">
                    <input type="text"
                           class="forum-search-box__input"
                           id="searchDibatasiInput"
                           placeholder="Cari nama samaran..."
                           oninput="filterDibatasiTable()">
                </div>
                <select class="forum-filter-select" id="filterDibatasiStatus" onchange="filterDibatasiTable()">
                    <option value="">Semua status</option>
                    <option value="Mute">Mute</option>
                    <option value="Peringatan">Peringatan</option>
                    <option value="Diblokir">Diblokir</option>
                </select>
            </div>

            <!-- Tabel Pengguna Dibatasi -->
            <div class="forum-table-responsive">
                <table class="forum-table" id="tableDibatasi">
                    <thead>
                        <tr>
                            <th>NAMA SAMARAN</th>
                            <th>STATUS</th>
                            <th>ALASAN</th>
                            <th>MULAI</th>
                            <th>BERAKHIR</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penggunaDibatasi as $user)
                            <tr class="forum-dibatasi-row" data-nama="{{ $user['nama'] }}" data-status="{{ $user['status'] }}">
                                <td>
                                    <span class="forum-table-author">{{ $user['nama'] }}</span>
                                </td>
                                <td>
                                    @if($user['status_type'] === 'mute')
                                        <span class="forum-badge-pill forum-badge-pill--mute">{{ $user['status'] }}</span>
                                    @elseif($user['status_type'] === 'warning-2')
                                        <span class="forum-badge-pill forum-badge-pill--warning-2">{{ $user['status'] }}</span>
                                    @elseif($user['status_type'] === 'warning-1')
                                        <span class="forum-badge-pill forum-badge-pill--warning-1">{{ $user['status'] }}</span>
                                    @elseif($user['status_type'] === 'blocked')
                                        <span class="forum-badge-pill forum-badge-pill--blocked">{{ $user['status'] }}</span>
                                    @endif
                                </td>
                                <td>{{ $user['alasan'] }}</td>
                                <td>{{ $user['mulai'] }}</td>
                                <td>{{ $user['berakhir'] }}</td>
                                <td>
                                    <div class="forum-table-actions-inline">
                                        @foreach($user['aksi'] as $action)
                                            @if($action === 'Cabut')
                                                <button type="button" class="table-action-link" onclick="cabutPembatasan('{{ $user['nama'] }}')">Cabut</button>
                                            @else
                                                <button type="button" class="table-action-link" onclick="openKelolaById({{ $user['id'] }}, '{{ $action }}')">{{ $action }}</button>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p class="forum-table-subnote">
                Hanya nama samaran yang tampil. Identitas asli tidak dibuka kepada moderator.
            </p>
        </div>
    @endif

</div>

<!-- ==========================================================================
     MODAL 4: KELOLA PEMBATASAN (SESUAI GAMBAR 2 TERBARU)
     ========================================================================== -->
<div class="forum-modal-backdrop" id="modalKelolaPembatasan" style="display: none;" onclick="if(event.target === this) closeKelolaPembatasanModal()">
    <div class="forum-modal-card" role="dialog" aria-modal="true" aria-labelledby="modalKelolaTitle">
        <button type="button" class="forum-modal__close" onclick="closeKelolaPembatasanModal()" aria-label="Tutup">✕</button>

        <h2 class="forum-modal__title forum-modal__title--neutral" id="modalKelolaTitle">Kelola Pembatasan</h2>

        <!-- User Info Box -->
        <div class="forum-kelola-user-box">
            <div class="forum-kelola-user-box__top">
                <span class="forum-kelola-user-box__name" id="kelolaUserName">Singa Malam</span>
                <span class="forum-badge-pill forum-badge-pill--mute" id="kelolaUserStatus">Mute 24 jam</span>
            </div>
            <p class="forum-kelola-user-box__sub" id="kelolaUserSub">
                Alasan: Ancaman · sisa 18 jam · 1 peringatan sebelumnya
            </p>
        </div>

        <!-- Tindakan -->
        <h3 class="forum-modal-section-title">Tindakan</h3>
        <div class="forum-modal-pills" id="kelolaTindakanPills">
            <button type="button" class="forum-modal-pill" onclick="selectKelolaTindakan(this, 'Cabut pembatasan')">Cabut pembatasan</button>
            <button type="button" class="forum-modal-pill is-selected" onclick="selectKelolaTindakan(this, 'Perpanjang mute')">Perpanjang mute</button>
            <button type="button" class="forum-modal-pill" onclick="selectKelolaTindakan(this, 'Blokir 7 hari')">Blokir 7 hari</button>
            <button type="button" class="forum-modal-pill" onclick="selectKelolaTindakan(this, 'Blokir permanen')">Blokir permanen</button>
        </div>

        <!-- Durasi -->
        <h3 class="forum-modal-section-title">Durasi</h3>
        <select class="forum-modal-select" id="kelolaDurasiSelect">
            <option value="24 jam" selected>24 jam</option>
            <option value="3 hari">3 hari</option>
            <option value="7 hari">7 hari</option>
            <option value="30 hari">30 hari</option>
            <option value="Permanen">Permanen</option>
        </select>

        <!-- Alasan (dicatat di riwayat moderasi) -->
        <h3 class="forum-modal-section-title">Alasan (dicatat di riwayat moderasi)</h3>
        <textarea class="forum-modal-textarea" id="kelolaAlasanInput" placeholder="Tulis alasan tindakan"></textarea>

        <!-- Pesan ke pengguna -->
        <h3 class="forum-modal-section-title">Pesan ke pengguna</h3>
        <textarea class="forum-modal-textarea" id="kelolaPesanInput">Aksesmu dibatasi lagi karena pelanggaran aturan forum. Mohon jaga forum tetap aman.</textarea>

        <!-- Callout Kuning -->
        <div class="forum-modal-warning-box" id="kelolaWarningBox">
            Blokir permanen sebaiknya ditinjau dulu sebelum diterapkan.
        </div>

        <!-- Footer -->
        <div class="forum-modal-footer">
            <div class="forum-modal-footer__actions">
                <button type="button" class="btn-modal-cancel btn-modal-cancel--maroon" onclick="closeKelolaPembatasanModal()">Batal</button>
                <button type="button" class="btn-modal-apply" onclick="confirmKelolaPembatasan()">Terapkan</button>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================================================
     MODAL 1: HAPUS KOMENTAR INI? (SESUAI GAMBAR 2)
     ========================================================================== -->
<div class="forum-modal-backdrop" id="modalHapusKomentar" style="display: none;" onclick="if(event.target === this) closeHapusKomentarModal()">
    <div class="forum-modal-card" role="dialog" aria-modal="true" aria-labelledby="modalKomentarTitle">
        <button type="button" class="forum-modal__close" onclick="closeHapusKomentarModal()" aria-label="Tutup">✕</button>

        <h2 class="forum-modal__title" id="modalKomentarTitle">Hapus komentar ini?</h2>

        <!-- Box Preview Komentar dengan Border Merah -->
        <div class="forum-modal-preview-box">
            <div class="forum-modal-preview-box__header">
                <strong id="modalKomentarAuthor">Anonim 123</strong>
                <span id="modalKomentarTime">· 11.20</span>
            </div>
            <p class="forum-modal-preview-box__body" id="modalKomentarBody">
                Liat aja nanti, aku cari tahu kamu siapa.
            </p>
        </div>

        <!-- Alasan Penghapusan -->
        <h3 class="forum-modal-section-title">Alasan penghapusan</h3>
        <div class="forum-modal-pills">
            <button type="button" class="forum-modal-pill is-selected" onclick="selectModalPill(this, 'modalKomentarMsg', 'Ancaman')">Ancaman</button>
            <button type="button" class="forum-modal-pill" onclick="selectModalPill(this, 'modalKomentarMsg', 'Perundungan')">Perundungan</button>
            <button type="button" class="forum-modal-pill" onclick="selectModalPill(this, 'modalKomentarMsg', 'Spam')">Spam</button>
            <button type="button" class="forum-modal-pill" onclick="selectModalPill(this, 'modalKomentarMsg', 'Lainnya')">Lainnya</button>
        </div>

        <!-- Tindakan Tambahan -->
        <h3 class="forum-modal-section-title">Tindakan tambahan</h3>
        <div class="forum-modal-pills">
            <label class="forum-checkbox-pill">
                <input type="checkbox" id="checkPeringatkanKomentar" checked>
                <span>Peringatkan akun</span>
            </label>
            <label class="forum-checkbox-pill">
                <input type="checkbox" id="checkMuteKomentar">
                <span>Mute 24 jam</span>
            </label>
        </div>

        <!-- Pesan ke Pengguna -->
        <h3 class="forum-modal-section-title">Pesan ke pengguna</h3>
        <textarea class="forum-modal-textarea" id="modalKomentarMsg">Komentarmu dihapus karena melanggar aturan komunitas (Ancaman). Mohon jaga komunitas tetap aman.</textarea>

        <!-- Footer -->
        <div class="forum-modal-footer">
            <p class="forum-modal-footer__note">
                Komentar yang dihapus tersimpan 30 hari di arsip moderasi dan bisa dipulihkan.
            </p>
            <div class="forum-modal-footer__actions">
                <button type="button" class="btn-modal-cancel" onclick="closeHapusKomentarModal()">Batal</button>
                <button type="button" class="btn-modal-danger" onclick="confirmHapusKomentar()">Hapus Komentar</button>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================================================
     MODAL 2: DETAIL POSTINGAN (SESUAI GAMBAR 4)
     ========================================================================== -->
<div class="forum-modal-backdrop" id="modalDetailPost" style="display: none;" onclick="if(event.target === this) closeDetailPostModal()">
    <div class="forum-modal-card forum-modal-card--wide" role="dialog" aria-modal="true" aria-labelledby="modalDetailTitle">
        <button type="button" class="forum-modal__close" onclick="closeDetailPostModal()" aria-label="Tutup">✕</button>

        <h2 class="forum-modal__title forum-modal__title--neutral" id="modalDetailTitle">Detail Postingan</h2>

        <!-- Kartu Postingan -->
        <div class="forum-detail-post-card">
            <div class="forum-detail-post-card__top">
                <div class="forum-detail-post-card__author">
                    <strong id="modalPostAuthor">Kopi Hangat</strong>
                    <span id="modalPostMeta">25 menit lalu · Magang & Dunia Kerja</span>
                </div>
                <span class="forum-badge-pill forum-badge-pill--aktif" id="modalPostStatus">Aktif</span>
            </div>
            <p class="forum-detail-post-card__body" id="modalPostContent">
                Hari ketiga magang dan aku masih takut bertanya ke mentor. Rasanya semua orang sudah paham, cuma aku yang bingung. Ada yang pernah merasa begini juga?
            </p>
            <div class="forum-detail-post-card__tags">
                <span class="forum-tag-pill">Curhat</span>
                <span class="forum-tag-pill">Magang</span>
                <span class="forum-detail-post-card__stats" id="modalPostStats">45 dukungan · 16 komentar</span>
            </div>
        </div>

        <!-- Komentar Terbaru -->
        <h3 class="forum-modal-section-title">Komentar terbaru</h3>
        <div class="forum-comments-group">
            <!-- Komentar 1 Normal -->
            <div class="forum-comment-card">
                <div class="forum-comment-card__top">
                    <span class="forum-comment-card__author">
                        <strong>Teh Hijau</strong> <span>· 20 menit lalu</span>
                    </span>
                </div>
                <p class="forum-comment-card__body">
                    Aku juga ngerasa gitu di minggu pertama. Pelan-pelan ya.
                </p>
            </div>

            <!-- Komentar 2 Dilaporkan (Border Merah) -->
            <div class="forum-comment-card forum-comment-card--flagged">
                <div class="forum-comment-card__top">
                    <span class="forum-comment-card__author">
                        <strong>Singa Malam</strong> <span>· 11.20</span>
                    </span>
                    <span class="forum-badge-pill forum-badge-pill--dilaporkan">Dilaporkan 4</span>
                </div>
                <p class="forum-comment-card__body">
                    Liat aja nanti, aku cari tahu kamu siapa.
                </p>
                <div class="forum-comment-card__actions">
                    <button type="button" class="forum-comment-action-btn" onclick="openHapusKomentarModalFromDetail()">Hapus komentar</button>
                    <button type="button" class="forum-comment-action-btn" onclick="alert('Peringatan dikirimkan ke akun!')">Peringatkan akun</button>
                </div>
            </div>
        </div>

        <p class="forum-modal-footer__note" style="max-width: 100%; margin-bottom: 20px;">
            Riwayat moderasi: belum ada tindakan pada postingan ini.
        </p>

        <!-- Footer Tombol -->
        <div class="forum-modal-footer">
            <div class="forum-modal-footer__actions">
                <button type="button" class="btn-modal-cancel" onclick="closeDetailPostModal()">Tutup</button>
                <button type="button" class="btn-modal-danger" onclick="switchToHapusPostModal()">Hapus Postingan</button>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================================================
     MODAL 3: HAPUS POSTINGAN INI? (SESUAI GAMBAR 5)
     ========================================================================== -->
<div class="forum-modal-backdrop" id="modalHapusPost" style="display: none;" onclick="if(event.target === this) closeHapusPostModal()">
    <div class="forum-modal-card" role="dialog" aria-modal="true" aria-labelledby="modalHapusPostTitle">
        <button type="button" class="forum-modal__close" onclick="closeHapusPostModal()" aria-label="Tutup">✕</button>

        <h2 class="forum-modal__title" id="modalHapusPostTitle">Hapus postingan ini?</h2>

        <!-- Box Preview Postingan dengan Border Merah -->
        <div class="forum-modal-preview-box">
            <div class="forum-modal-preview-box__header">
                <strong id="modalHapusPostAuthor">Kopi Hangat</strong>
                <span id="modalHapusPostMeta">· 25 menit lalu</span>
            </div>
            <p class="forum-modal-preview-box__body" id="modalHapusPostBody">
                Hari ketiga magang dan aku masih takut bertanya ke mentor...
            </p>
        </div>

        <!-- Warning Callout Kuning -->
        <div class="forum-modal-warning-box">
            <span id="modalHapusPostWarning">16 komentar pada postingan ini ikut disembunyikan dari komunitas.</span>
        </div>

        <!-- Alasan Penghapusan -->
        <h3 class="forum-modal-section-title">Alasan penghapusan</h3>
        <div class="forum-modal-pills">
            <button type="button" class="forum-modal-pill" onclick="selectModalPill(this, 'modalPostMsg', 'Perundungan')">Perundungan</button>
            <button type="button" class="forum-modal-pill" onclick="selectModalPill(this, 'modalPostMsg', 'Ancaman')">Ancaman</button>
            <button type="button" class="forum-modal-pill" onclick="selectModalPill(this, 'modalPostMsg', 'Spam')">Spam</button>
            <button type="button" class="forum-modal-pill is-selected" onclick="selectModalPill(this, 'modalPostMsg', 'Melanggar aturan lain')">Melanggar aturan lain</button>
        </div>

        <!-- Tindakan Tambahan -->
        <h3 class="forum-modal-section-title">Tindakan tambahan</h3>
        <div class="forum-modal-pills">
            <label class="forum-checkbox-pill">
                <input type="checkbox" id="checkPeringatkanPost">
                <span>Peringatkan akun</span>
            </label>
            <label class="forum-checkbox-pill">
                <input type="checkbox" id="checkMutePost">
                <span>Mute 24 jam</span>
            </label>
        </div>

        <!-- Pesan ke Pengguna -->
        <h3 class="forum-modal-section-title">Pesan ke pengguna</h3>
        <textarea class="forum-modal-textarea" id="modalPostMsg">Postinganmu dihapus karena melanggar aturan komunitas. Mohon jaga kom tetap aman.</textarea>

        <!-- Footer -->
        <div class="forum-modal-footer">
            <p class="forum-modal-footer__note">
                Disimpan 30 hari di arsip moderasi dan bisa dipulihkan.
            </p>
            <div class="forum-modal-footer__actions">
                <button type="button" class="btn-modal-cancel" onclick="closeHapusPostModal()">Batal</button>
                <button type="button" class="btn-modal-danger" onclick="confirmHapusPost()">Hapus Postingan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Data Lists
    const antreanData = @json($antreanLaporan ?? []);
    const kontenData = @json($semuaKonten ?? []);
    const dibatasiData = @json($penggunaDibatasi ?? []);

    function selectQueueById(id, element) {
        const item = antreanData.find(x => x.id == id);
        if (item) selectQueueItem(item, element);
    }

    function openDetailPostById(id) {
        const item = kontenData.find(x => x.id == id);
        if (item) openDetailPostModal(item);
    }

    function openHapusPostById(id) {
        const item = kontenData.find(x => x.id == id);
        if (item) openHapusPostModal(item);
    }

    function openKelolaById(id, action) {
        const item = dibatasiData.find(x => x.id == id);
        if (item) openKelolaPembatasanModal(item, action);
    }

    // State Moderasi
    let currentSelectedItem = {
        id: 1,
        penulis: 'Singa Malam',
        waktu: '11.20',
        isi: 'Liat aja nanti, aku cari tahu kamu siapa.'
    };

    // Filter Antrean Laporan (Semua, Perundungan, Ancaman, Berisiko, Spam)
    function filterQueue(btn, category) {
        document.querySelectorAll('.forum-chip-btn').forEach(b => b.classList.remove('is-active'));
        btn.classList.add('is-active');

        const cards = document.querySelectorAll('.forum-queue-card');
        cards.forEach(card => {
            if (category === 'semua' || card.getAttribute('data-kategori') === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Pemilihan item antrean untuk update panel kanan
    function selectQueueItem(data, element) {
        document.querySelectorAll('.forum-queue-card').forEach(c => c.classList.remove('is-selected'));
        if (element) {
            element.classList.add('is-selected');
        }

        currentSelectedItem = data;

        // Update Title & Subtitle
        document.getElementById('detailTitle').textContent = data.jenis === 'Komentar' ? 'Komentar Dilaporkan' : 'Postingan Dilaporkan';
        document.getElementById('detailSubtitle').textContent = data.sub_laporan || 'Menunggu tindakan';
        document.getElementById('detailHistoryNote').textContent = data.riwayat || '';

        // Update Konteks
        if (data.post_konteks) {
            document.getElementById('postAuthor').textContent = data.post_konteks.penulis;
            document.getElementById('postMeta').textContent = data.post_konteks.waktu + ' - ' + data.post_konteks.komunitas;
            document.getElementById('postContent').textContent = data.post_konteks.isi;
            document.getElementById('detailPostBox').style.display = 'block';
        }

        if (data.komentar_konteks) {
            document.getElementById('commentAuthor').textContent = data.komentar_konteks.penulis;
            document.getElementById('commentMeta').textContent = data.komentar_konteks.waktu + ' Membalas ' + data.komentar_konteks.membalas;
            document.getElementById('commentContent').textContent = data.komentar_konteks.isi;
            document.getElementById('detailCommentBox').style.display = 'block';
            document.getElementById('btnHapusAction').textContent = 'HAPUS KOMENTAR';
        } else {
            document.getElementById('detailCommentBox').style.display = 'none';
            document.getElementById('btnHapusAction').textContent = 'HAPUS POSTINGAN';
        }
    }

    // Modal Hapus Komentar
    function openHapusKomentarModal() {
        const author = currentSelectedItem.penulis || 'Anonim 123';
        const time = currentSelectedItem.waktu || '11.20';
        const body = currentSelectedItem.isi || 'Liat aja nanti, aku cari tahu kamu siapa.';

        openHapusKomentarModalWithData(author, time, body);
    }

    function openHapusKomentarModalWithData(author, time, body) {
        document.getElementById('modalKomentarAuthor').textContent = author;
        document.getElementById('modalKomentarTime').textContent = '· ' + time;
        document.getElementById('modalKomentarBody').textContent = body;
        document.getElementById('modalHapusKomentar').style.display = 'flex';
    }

    function closeHapusKomentarModal() {
        document.getElementById('modalHapusKomentar').style.display = 'none';
    }

    function confirmHapusKomentar() {
        alert('Komentar berhasil dihapus dan dipindahkan ke arsip moderasi.');
        closeHapusKomentarModal();
    }

    // Modal Detail Postingan
    let activePostDetail = null;

    function openDetailPostModal(post) {
        activePostDetail = post;
        document.getElementById('modalPostAuthor').textContent = post.alias || post.penulis;
        document.getElementById('modalPostMeta').textContent = post.waktu + ' · ' + post.komunitas;
        document.getElementById('modalPostStatus').textContent = post.status;
        document.getElementById('modalPostContent').textContent = post.isi_full || post.isi;
        document.getElementById('modalPostStats').textContent = (post.dukungan || 45) + ' dukungan · ' + (post.komentar_count || 16) + ' komentar';

        document.getElementById('modalDetailPost').style.display = 'flex';
    }

    function closeDetailPostModal() {
        document.getElementById('modalDetailPost').style.display = 'none';
    }

    function openHapusKomentarModalFromDetail() {
        closeDetailPostModal();
        openHapusKomentarModalWithData('Singa Malam', '11.20', 'Liat aja nanti, aku cari tahu kamu siapa.');
    }

    function switchToHapusPostModal() {
        closeDetailPostModal();
        if (activePostDetail) {
            openHapusPostModal(activePostDetail);
        } else {
            openHapusPostModal({
                alias: 'Kopi Hangat',
                waktu: '25 menit lalu',
                isi: 'Hari ketiga magang dan aku masih takut bertanya ke mentor...',
                komentar_count: 16
            });
        }
    }

    // Modal Hapus Postingan
    function openHapusPostModal(post) {
        document.getElementById('modalHapusPostAuthor').textContent = post.alias || post.penulis || 'Kopi Hangat';
        document.getElementById('modalHapusPostMeta').textContent = '· ' + (post.waktu || '25 menit lalu');
        document.getElementById('modalHapusPostBody').textContent = post.isi || post.isi_full;
        document.getElementById('modalHapusPostWarning').textContent = (post.komentar_count || 16) + ' komentar pada postingan ini ikut disembunyikan dari komunitas.';

        document.getElementById('modalHapusPost').style.display = 'flex';
    }

    function closeHapusPostModal() {
        document.getElementById('modalHapusPost').style.display = 'none';
    }

    function confirmHapusPost() {
        alert('Postingan berhasil dihapus beserta komentar di dalamnya.');
        closeHapusPostModal();
    }

    // Pills interaktif pada modal
    function selectModalPill(btn, textareaId, category) {
        const parent = btn.parentElement;
        parent.querySelectorAll('.forum-modal-pill').forEach(p => p.classList.remove('is-selected'));
        btn.classList.add('is-selected');

        const textarea = document.getElementById(textareaId);
        if (textarea) {
            if (textareaId === 'modalKomentarMsg') {
                textarea.value = `Komentarmu dihapus karena melanggar aturan komunitas (${category}). Mohon jaga komunitas tetap aman.`;
            } else {
                textarea.value = `Postinganmu dihapus karena melanggar aturan komunitas (${category}). Mohon jaga komunitas tetap aman.`;
            }
        }
    }

    // Aksi Moderasi
    function abaikanLaporan() {
        alert('Laporan diabaikan dan ditandai selesai.');
    }

    function muteUser(hours) {
        alert(`Akun pengguna dibisukan selama ${hours} jam.`);
    }

    function peringatkanAkun() {
        alert('Peringatan resmi moderator telah dikirimkan ke akun pengguna.');
    }

    // Filter Table Semua Konten
    function filterKontenTable() {
        const search = document.getElementById('searchKontenInput').value.toLowerCase();
        const jenis = document.getElementById('filterJenis').value;
        const komunitas = document.getElementById('filterKomunitas').value;
        const status = document.getElementById('filterStatus').value;

        const rows = document.querySelectorAll('#tableKonten tbody tr');
        rows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            const rowJenis = row.getAttribute('data-jenis');
            const rowKomunitas = row.getAttribute('data-komunitas');
            const rowStatus = row.getAttribute('data-status');

            const matchSearch = rowText.includes(search);
            const matchJenis = !jenis || rowJenis === jenis;
            const matchKomunitas = !komunitas || rowKomunitas === komunitas;
            const matchStatus = !status || rowStatus.includes(status);

            if (matchSearch && matchJenis && matchKomunitas && matchStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Modal Kelola Pembatasan (Tab 3)
    let currentKelolaUser = null;

    function openKelolaPembatasanModal(user, action) {
        currentKelolaUser = user;
        document.getElementById('kelolaUserName').textContent = user.nama;
        
        const statusBadge = document.getElementById('kelolaUserStatus');
        statusBadge.textContent = user.status;
        statusBadge.className = 'forum-badge-pill';
        if (user.status_type === 'mute') statusBadge.classList.add('forum-badge-pill--mute');
        else if (user.status_type === 'warning-2') statusBadge.classList.add('forum-badge-pill--warning-2');
        else if (user.status_type === 'warning-1') statusBadge.classList.add('forum-badge-pill--warning-1');
        else if (user.status_type === 'blocked') statusBadge.classList.add('forum-badge-pill--blocked');

        document.getElementById('kelolaUserSub').textContent = user.sub_info || `Alasan: ${user.alasan}`;

        // Set active pill based on action
        const pills = document.querySelectorAll('#kelolaTindakanPills .forum-modal-pill');
        pills.forEach(p => {
            p.classList.remove('is-selected');
            if (action && p.textContent.toLowerCase().includes(action.toLowerCase())) {
                p.classList.add('is-selected');
            }
        });
        if (!document.querySelector('#kelolaTindakanPills .forum-modal-pill.is-selected')) {
            pills[1].classList.add('is-selected'); // Default 'Perpanjang mute'
        }

        document.getElementById('modalKelolaPembatasan').style.display = 'flex';
    }

    function closeKelolaPembatasanModal() {
        document.getElementById('modalKelolaPembatasan').style.display = 'none';
    }

    function selectKelolaTindakan(btn, actionName) {
        document.querySelectorAll('#kelolaTindakanPills .forum-modal-pill').forEach(p => p.classList.remove('is-selected'));
        btn.classList.add('is-selected');

        const warningBox = document.getElementById('kelolaWarningBox');
        if (actionName === 'Blokir permanen') {
            warningBox.style.display = 'block';
            document.getElementById('kelolaDurasiSelect').value = 'Permanen';
        } else if (actionName === 'Blokir 7 hari') {
            warningBox.style.display = 'none';
            document.getElementById('kelolaDurasiSelect').value = '7 hari';
        } else if (actionName === 'Perpanjang mute') {
            warningBox.style.display = 'none';
            document.getElementById('kelolaDurasiSelect').value = '24 jam';
        } else {
            warningBox.style.display = 'none';
        }
    }

    function confirmKelolaPembatasan() {
        const userName = currentKelolaUser ? currentKelolaUser.nama : 'pengguna';
        alert(`Perubahan pembatasan untuk ${userName} berhasil diterapkan!`);
        closeKelolaPembatasanModal();
    }

    function cabutPembatasan(userName) {
        if (confirm(`Cabut semua pembatasan aktif untuk akun ${userName}?`)) {
            alert(`Pembatasan untuk ${userName} berhasil dicabut.`);
        }
    }

    // Filter Table Pengguna Dibatasi
    function filterDibatasiTable() {
        const search = document.getElementById('searchDibatasiInput').value.toLowerCase();
        const status = document.getElementById('filterDibatasiStatus').value.toLowerCase();

        const rows = document.querySelectorAll('#tableDibatasi tbody tr');
        rows.forEach(row => {
            const rowNama = (row.getAttribute('data-nama') || '').toLowerCase();
            const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();
            const rowText = row.textContent.toLowerCase();

            const matchSearch = rowText.includes(search) || rowNama.includes(search);
            const matchStatus = !status || rowStatus.includes(status);

            if (matchSearch && matchStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endpush
