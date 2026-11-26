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

    <!-- Alert Flash & Validasi -->
    @if (session('success'))
        <div style="background-color: #D4EDDA; border: 1px solid #C3E6CB; color: #155724; padding: 12px 18px; border-radius: 10px; margin-bottom: 18px; font-weight: 600; font-size: 13.5px; display: flex; align-items: center; gap: 8px;">
            <span class="material-icons-round" style="font-size: 18px;">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div style="background-color: #F8D7DA; border: 1px solid #F5C6CB; color: #721C24; padding: 12px 18px; border-radius: 10px; margin-bottom: 18px; font-weight: 600; font-size: 13.5px; display: flex; align-items: center; gap: 8px;">
            <span class="material-icons-round" style="font-size: 18px;">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div style="background-color: #F8D7DA; border: 1px solid #F5C6CB; color: #721C24; padding: 12px 18px; border-radius: 10px; margin-bottom: 18px; font-weight: 500; font-size: 13.5px;">
            <div style="font-weight: 700; margin-bottom: 4px;">Terjadi kesalahan input:</div>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Underline Navigation Tabs -->
    <div class="forum-nav-tabs-wrapper">
        <nav class="forum-nav-tabs" role="tablist">
            <a href="{{ route('admin.forum.index', ['tab' => 'antrean']) }}"
               class="forum-nav-tab {{ $tab === 'antrean' ? 'is-active' : '' }}"
               role="tab">
                <span>Antrean Laporan</span>
                <span class="forum-tab-badge">{{ $stats['laporan_baru'] }}</span>
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
                <span class="forum-stat-card__label">Konten Dihapus</span>
                <span class="forum-stat-card__value">{{ $stats['dihapus_hari_ini'] }}</span>
            </div>
            <div class="forum-stat-card">
                <span class="forum-stat-card__label">Pengguna Dibatasi</span>
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
                    @forelse($antreanLaporan as $index => $item)
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
                            <span class="forum-queue-card__footer">
                                {{ $item['jumlah_laporan'] }} · {{ ucfirst($item['status_laporan']) }}
                            </span>
                        </div>
                    @empty
                        <p style="font-size: 13px; color: #6B7280; text-align: center; padding: 20px 8px; margin: 0;">
                            Belum ada laporan konten yang masuk.
                        </p>
                    @endforelse
                </div>
            </aside>

            <!-- Kolom Kanan: Detail Moderasi -->
            <main class="forum-detail-panel" id="detailPanel">
                @if(!empty($antreanLaporan))
                    @php $firstItem = $antreanLaporan[0]; @endphp
                    <h2 class="forum-detail-panel__title" id="detailTitle">
                        {{ $firstItem['jenis'] === 'Komentar' ? 'Komentar Dilaporkan' : 'Postingan Dilaporkan' }}
                    </h2>
                    <p class="forum-detail-panel__subtitle" id="detailSubtitle">
                        {{ $firstItem['sub_laporan'] }}
                    </p>

                    <div class="forum-detail-divider"></div>

                    <h3 class="forum-detail-section-label" id="detailSectionLabel">Konteks Postingan</h3>

                    <!-- Kartu Postingan Utama -->
                    <div class="forum-context-post" id="detailPostBox">
                        <div class="forum-context-post__header">
                            <strong id="postAuthor">{{ $firstItem['post_konteks']['penulis'] }}</strong>
                            <span id="postMeta">{{ $firstItem['post_konteks']['waktu'] }} - {{ $firstItem['post_konteks']['komunitas'] }}</span>
                        </div>
                        <p class="forum-context-post__content" id="postContent">
                            {{ $firstItem['post_konteks']['isi'] }}
                        </p>
                    </div>

                    <!-- Box Komentar Dilaporkan (Nested Merah) -->
                    <div class="forum-context-comment" id="detailCommentBox" style="{{ $firstItem['komentar_konteks'] ? '' : 'display: none;' }}">
                        <div class="forum-context-comment__header">
                            <strong id="commentAuthor">{{ $firstItem['komentar_konteks']['penulis'] ?? '' }}</strong>
                            <span id="commentMeta">
                                {{ $firstItem['komentar_konteks'] ? ($firstItem['komentar_konteks']['waktu'] . ' Membalas ' . $firstItem['komentar_konteks']['membalas']) : '' }}
                            </span>
                        </div>
                        <div class="forum-comment-quote-box">
                            <span id="commentContent">{{ $firstItem['komentar_konteks']['isi'] ?? '' }}</span>
                        </div>
                    </div>

                    <!-- Riwayat Catatan Moderasi -->
                    <p class="forum-history-note" id="detailHistoryNote">
                        {{ $firstItem['riwayat'] }}
                    </p>

                    <!-- Tombol Aksi Moderasi -->
                    <div class="forum-detail-actions">
                        <form id="formTolakLaporan" method="POST" action="{{ route('admin.forum.laporan.tolak', $firstItem['id_post']) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-mod-outline" id="btnTolakLaporan"
                                    style="{{ $firstItem['status_laporan'] === 'menunggu' ? '' : 'display: none;' }}"
                                    onclick="return confirm('Tolak semua laporan menunggu untuk konten ini?')">
                                Tolak Laporan
                            </button>
                        </form>

                        <form id="formTindakLaporan" method="POST" action="{{ route('admin.forum.laporan.tindak', $firstItem['id_post']) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-mod-outline" id="btnTindakLaporan"
                                    style="{{ $firstItem['status_laporan'] === 'menunggu' ? '' : 'display: none;' }}">
                                Tandai Ditindak
                            </button>
                        </form>

                        <form id="formMuteLaporan" method="POST" action="{{ route('admin.forum.sanksi.store') }}" style="display:inline;">
                            @csrf
                            <input type="hidden" name="id_user" id="muteInputUser" value="{{ $firstItem['id_user'] }}">
                            <input type="hidden" name="id_laporan" id="muteInputLaporan" value="{{ $firstItem['id_laporan'] }}">
                            <input type="hidden" name="jenis" value="mute">
                            <input type="hidden" name="durasi_jam" value="24">
                            <input type="hidden" name="alasan" id="muteInputAlasan" value="{{ $firstItem['kategori'] }}">
                            <button type="submit" class="btn-mod-outline" onclick="return confirm('Mute akun ini selama 24 jam?')">
                                Mute 24 Jam
                            </button>
                        </form>

                        <form id="formPeringatkanLaporan" method="POST" action="{{ route('admin.forum.sanksi.store') }}" style="display:inline;">
                            @csrf
                            <input type="hidden" name="id_user" id="peringatanInputUser" value="{{ $firstItem['id_user'] }}">
                            <input type="hidden" name="id_laporan" id="peringatanInputLaporan" value="{{ $firstItem['id_laporan'] }}">
                            <input type="hidden" name="jenis" value="peringatan">
                            <input type="hidden" name="alasan" id="peringatanInputAlasan" value="{{ $firstItem['kategori'] }}">
                            <button type="submit" class="btn-mod-outline" onclick="return confirm('Berikan peringatan resmi ke akun ini?')">
                                Peringatkan Akun
                            </button>
                        </form>

                        <button type="button" class="btn-mod-danger" id="btnHapusAction"
                                style="{{ $firstItem['status_konten'] === 'dihapus' ? 'display: none;' : '' }}"
                                onclick="openHapusAntreanModal()">
                            {{ $firstItem['jenis'] === 'Komentar' ? 'HAPUS KOMENTAR' : 'HAPUS POSTINGAN' }}
                        </button>

                        <form id="formPulihkanAntrean" method="POST" action="{{ route('admin.forum.posts.pulihkan', $firstItem['id_post']) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-mod-outline" id="btnPulihkanAction"
                                    style="{{ $firstItem['status_konten'] === 'dihapus' ? '' : 'display: none;' }}"
                                    onclick="return confirm('Pulihkan konten ini ke status aktif?')">
                                PULIHKAN KONTEN
                            </button>
                        </form>
                    </div>
                @else
                    <h2 class="forum-detail-panel__title">Antrean Laporan Kosong</h2>
                    <p class="forum-detail-panel__subtitle">
                        Tidak ada laporan konten yang perlu ditinjau saat ini.
                    </p>
                @endif
            </main>

        </div>

    @elseif($tab === 'semua-konten')
        <!-- Tab 2: Semua Konten Table -->
        <div class="forum-semua-konten-container">

            <!-- Filter Toolbar -->
            <form method="GET" action="{{ route('admin.forum.index') }}" class="forum-filter-toolbar">
                <input type="hidden" name="tab" value="semua-konten">
                <div class="forum-search-box">
                    <input type="text"
                           name="search"
                           value="{{ $filters['search'] ?? '' }}"
                           class="forum-search-box__input"
                           id="searchKontenInput"
                           placeholder="Cari isi konten atau nama samaran...">
                </div>
                <select name="jenis" class="forum-filter-select" id="filterJenis" onchange="this.form.submit()">
                    <option value="">Semua jenis</option>
                    <option value="Postingan" {{ ($filters['jenis'] ?? '') === 'Postingan' ? 'selected' : '' }}>Postingan</option>
                    <option value="Komentar" {{ ($filters['jenis'] ?? '') === 'Komentar' ? 'selected' : '' }}>Komentar</option>
                </select>
                <select name="id_komunitas" class="forum-filter-select" id="filterKomunitas" onchange="this.form.submit()">
                    <option value="">Semua komunitas</option>
                    @foreach($komunitasList as $kom)
                        <option value="{{ $kom->id_komunitas }}" {{ (string) ($filters['id_komunitas'] ?? '') === (string) $kom->id_komunitas ? 'selected' : '' }}>
                            {{ $kom->nama }}
                        </option>
                    @endforeach
                </select>
                <select name="status" class="forum-filter-select" id="filterStatus" onchange="this.form.submit()">
                    <option value="">Semua status</option>
                    <option value="aktif" {{ ($filters['status'] ?? '') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="dilaporkan" {{ ($filters['status'] ?? '') === 'dilaporkan' ? 'selected' : '' }}>Dilaporkan</option>
                    <option value="berisiko" {{ ($filters['status'] ?? '') === 'berisiko' ? 'selected' : '' }}>Berisiko</option>
                    <option value="dihapus" {{ ($filters['status'] ?? '') === 'dihapus' ? 'selected' : '' }}>Dihapus</option>
                </select>
            </form>

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
                        @forelse($semuaKonten as $row)
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
                                                <button type="button" class="table-action-link" onclick="openHapusKomentarById({{ $row['id'] }})">Hapus</button>
                                            @endif
                                        @endif
                                        @if(in_array('pulihkan', $row['aksi']))
                                            <form method="POST" action="{{ route('admin.forum.posts.pulihkan', $row['id_post']) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="table-action-link" onclick="return confirm('Pulihkan konten ini ke status aktif?')">Pulihkan</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; color: #6B7280; padding: 24px;">
                                    Belum ada konten forum yang sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="forum-pagination-footer">
                <span class="forum-pagination-info">
                    @if($semuaKontenPaginator->total() > 0)
                        Menampilkan {{ $semuaKontenPaginator->firstItem() }}-{{ $semuaKontenPaginator->lastItem() }} dari {{ $semuaKontenPaginator->total() }} konten
                    @else
                        Menampilkan 0 dari 0 konten
                    @endif
                </span>
                @if($semuaKontenPaginator->hasPages())
                    <div class="forum-pagination-btns">
                        @if($semuaKontenPaginator->onFirstPage())
                            <span class="forum-page-btn" style="opacity: 0.4; pointer-events: none;">‹</span>
                        @else
                            <a href="{{ $semuaKontenPaginator->previousPageUrl() }}" class="forum-page-btn" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">‹</a>
                        @endif

                        @for($page = 1; $page <= $semuaKontenPaginator->lastPage(); $page++)
                            <a href="{{ $semuaKontenPaginator->url($page) }}"
                               class="forum-page-btn {{ $page === $semuaKontenPaginator->currentPage() ? 'is-active' : '' }}"
                               style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">
                                {{ $page }}
                            </a>
                        @endfor

                        @if($semuaKontenPaginator->hasMorePages())
                            <a href="{{ $semuaKontenPaginator->nextPageUrl() }}" class="forum-page-btn" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">›</a>
                        @else
                            <span class="forum-page-btn" style="opacity: 0.4; pointer-events: none;">›</span>
                        @endif
                    </div>
                @endif
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
                        @forelse($penggunaDibatasi as $user)
                            <tr class="forum-dibatasi-row" data-nama="{{ $user['nama'] }}" data-status="{{ $user['status'] }}">
                                <td>
                                    <span class="forum-table-author">{{ $user['nama'] }}</span>
                                </td>
                                <td>
                                    @if($user['status_type'] === 'mute')
                                        <span class="forum-badge-pill forum-badge-pill--mute">{{ $user['status'] }}</span>
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
                                                <form method="POST" action="{{ route('admin.forum.sanksi.cabut', $user['id_sanksi']) }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="table-action-link" onclick="return confirm('Cabut pembatasan aktif untuk akun {{ addslashes($user['nama']) }}?')">Cabut</button>
                                                </form>
                                            @else
                                                <button type="button" class="table-action-link" onclick="openKelolaById({{ $user['id'] }}, '{{ $action }}')">{{ $action }}</button>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: #6B7280; padding: 24px;">
                                    Tidak ada pengguna dengan sanksi mute atau blokir yang sedang aktif.
                                </td>
                            </tr>
                        @endforelse
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
     MODAL 4: KELOLA PEMBATASAN (TAB 3)
     ========================================================================== -->
<div class="forum-modal-backdrop" id="modalKelolaPembatasan" style="display: none;" onclick="if(event.target === this) closeKelolaPembatasanModal()">
    <div class="forum-modal-card" role="dialog" aria-modal="true" aria-labelledby="modalKelolaTitle">
        <button type="button" class="forum-modal__close" onclick="closeKelolaPembatasanModal()" aria-label="Tutup">✕</button>

        <h2 class="forum-modal__title forum-modal__title--neutral" id="modalKelolaTitle">Kelola Pembatasan</h2>

        <!-- User Info Box -->
        <div class="forum-kelola-user-box">
            <div class="forum-kelola-user-box__top">
                <span class="forum-kelola-user-box__name" id="kelolaUserName">-</span>
                <span class="forum-badge-pill forum-badge-pill--mute" id="kelolaUserStatus">-</span>
            </div>
            <p class="forum-kelola-user-box__sub" id="kelolaUserSub">-</p>
        </div>

        <form id="formKelolaPembatasan" method="POST" action="{{ route('admin.forum.sanksi.store') }}">
            @csrf
            <input type="hidden" name="id_user" id="kelolaInputUserId" value="">
            <input type="hidden" name="jenis" id="kelolaInputJenis" value="mute">

            <!-- Tindakan -->
            <h3 class="forum-modal-section-title">Tindakan</h3>
            <div class="forum-modal-pills" id="kelolaTindakanPills">
                <button type="button" class="forum-modal-pill" onclick="selectKelolaTindakan(this, 'Cabut pembatasan')">Cabut pembatasan</button>
                <button type="button" class="forum-modal-pill is-selected" onclick="selectKelolaTindakan(this, 'Perpanjang mute')">Perpanjang mute</button>
                <button type="button" class="forum-modal-pill" onclick="selectKelolaTindakan(this, 'Blokir 7 hari')">Blokir 7 hari</button>
                <button type="button" class="forum-modal-pill" onclick="selectKelolaTindakan(this, 'Blokir permanen')">Blokir permanen</button>
            </div>

            <!-- Durasi -->
            <div id="kelolaDurasiWrapper">
                <h3 class="forum-modal-section-title">Durasi</h3>
                <select class="forum-modal-select" name="durasi_jam" id="kelolaDurasiSelect">
                    <option value="24" selected>24 jam</option>
                    <option value="72">3 hari</option>
                    <option value="168">7 hari</option>
                    <option value="720">30 hari</option>
                    <option value="">Permanen</option>
                </select>
            </div>

            <!-- Alasan (dicatat di riwayat moderasi) -->
            <div id="kelolaAlasanWrapper">
                <h3 class="forum-modal-section-title">Alasan (dicatat di riwayat moderasi)</h3>
                <textarea class="forum-modal-textarea" name="alasan" id="kelolaAlasanInput" placeholder="Tulis alasan tindakan" required>Pelanggaran aturan forum berulang</textarea>
            </div>

            <!-- Callout Kuning -->
            <div class="forum-modal-warning-box" id="kelolaWarningBox" style="display: none;">
                Blokir permanen sebaiknya ditinjau dulu sebelum diterapkan.
            </div>

            <!-- Footer -->
            <div class="forum-modal-footer">
                <div class="forum-modal-footer__actions">
                    <button type="button" class="btn-modal-cancel btn-modal-cancel--maroon" onclick="closeKelolaPembatasanModal()">Batal</button>
                    <button type="submit" class="btn-modal-apply">Terapkan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ==========================================================================
     MODAL 1: HAPUS KOMENTAR INI?
     ========================================================================== -->
<div class="forum-modal-backdrop" id="modalHapusKomentar" style="display: none;" onclick="if(event.target === this) closeHapusKomentarModal()">
    <div class="forum-modal-card" role="dialog" aria-modal="true" aria-labelledby="modalKomentarTitle">
        <button type="button" class="forum-modal__close" onclick="closeHapusKomentarModal()" aria-label="Tutup">✕</button>

        <h2 class="forum-modal__title" id="modalKomentarTitle">Hapus komentar ini?</h2>

        <!-- Box Preview Komentar dengan Border Merah -->
        <div class="forum-modal-preview-box">
            <div class="forum-modal-preview-box__header">
                <strong id="modalKomentarAuthor">-</strong>
                <span id="modalKomentarTime">· -</span>
            </div>
            <p class="forum-modal-preview-box__body" id="modalKomentarBody">-</p>
        </div>

        <form id="formHapusKomentar" method="POST" action="">
            @csrf
            <input type="hidden" name="alasan" id="inputAlasanHapusKomentar" value="Ancaman">

            <!-- Alasan Penghapusan -->
            <h3 class="forum-modal-section-title">Alasan penghapusan</h3>
            <div class="forum-modal-pills">
                <button type="button" class="forum-modal-pill is-selected" onclick="selectModalPill(this, 'inputAlasanHapusKomentar', 'Ancaman')">Ancaman</button>
                <button type="button" class="forum-modal-pill" onclick="selectModalPill(this, 'inputAlasanHapusKomentar', 'Perundungan')">Perundungan</button>
                <button type="button" class="forum-modal-pill" onclick="selectModalPill(this, 'inputAlasanHapusKomentar', 'Spam')">Spam</button>
                <button type="button" class="forum-modal-pill" onclick="selectModalPill(this, 'inputAlasanHapusKomentar', 'Lainnya')">Lainnya</button>
            </div>

            <!-- Tindakan Tambahan -->
            <h3 class="forum-modal-section-title">Tindakan tambahan</h3>
            <div class="forum-modal-pills">
                <label class="forum-checkbox-pill">
                    <input type="checkbox" name="peringatkan" value="1" id="checkPeringatkanKomentar">
                    <span>Peringatkan akun</span>
                </label>
                <label class="forum-checkbox-pill">
                    <input type="checkbox" name="mute_24_jam" value="1" id="checkMuteKomentar">
                    <span>Mute 24 jam</span>
                </label>
            </div>

            <!-- Footer -->
            <div class="forum-modal-footer">
                <p class="forum-modal-footer__note">
                    Komentar yang dihapus tersimpan di arsip moderasi dan bisa dipulihkan.
                </p>
                <div class="forum-modal-footer__actions">
                    <button type="button" class="btn-modal-cancel" onclick="closeHapusKomentarModal()">Batal</button>
                    <button type="submit" class="btn-modal-danger">Hapus Komentar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ==========================================================================
     MODAL 2: DETAIL POSTINGAN
     ========================================================================== -->
<div class="forum-modal-backdrop" id="modalDetailPost" style="display: none;" onclick="if(event.target === this) closeDetailPostModal()">
    <div class="forum-modal-card forum-modal-card--wide" role="dialog" aria-modal="true" aria-labelledby="modalDetailTitle">
        <button type="button" class="forum-modal__close" onclick="closeDetailPostModal()" aria-label="Tutup">✕</button>

        <h2 class="forum-modal__title forum-modal__title--neutral" id="modalDetailTitle">Detail Postingan</h2>

        <!-- Kartu Postingan -->
        <div class="forum-detail-post-card">
            <div class="forum-detail-post-card__top">
                <div class="forum-detail-post-card__author">
                    <strong id="modalPostAuthor">-</strong>
                    <span id="modalPostMeta">-</span>
                </div>
                <span class="forum-badge-pill forum-badge-pill--aktif" id="modalPostStatus">Aktif</span>
            </div>
            <p class="forum-detail-post-card__body" id="modalPostContent">-</p>
            <div class="forum-detail-post-card__tags">
                <span class="forum-detail-post-card__stats" id="modalPostStats">0 dukungan · 0 komentar</span>
            </div>
        </div>

        <!-- Komentar Terbaru -->
        <h3 class="forum-modal-section-title">Komentar terbaru</h3>
        <div class="forum-comments-group" id="modalPostCommentsGroup"></div>

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
     MODAL 3: HAPUS POSTINGAN INI?
     ========================================================================== -->
<div class="forum-modal-backdrop" id="modalHapusPost" style="display: none;" onclick="if(event.target === this) closeHapusPostModal()">
    <div class="forum-modal-card" role="dialog" aria-modal="true" aria-labelledby="modalHapusPostTitle">
        <button type="button" class="forum-modal__close" onclick="closeHapusPostModal()" aria-label="Tutup">✕</button>

        <h2 class="forum-modal__title" id="modalHapusPostTitle">Hapus postingan ini?</h2>

        <!-- Box Preview Postingan dengan Border Merah -->
        <div class="forum-modal-preview-box">
            <div class="forum-modal-preview-box__header">
                <strong id="modalHapusPostAuthor">-</strong>
                <span id="modalHapusPostMeta">· -</span>
            </div>
            <p class="forum-modal-preview-box__body" id="modalHapusPostBody">-</p>
        </div>

        <!-- Warning Callout Kuning -->
        <div class="forum-modal-warning-box">
            <span id="modalHapusPostWarning">0 komentar pada postingan ini ikut terdampak saat postingan dihapus.</span>
        </div>

        <form id="formHapusPost" method="POST" action="">
            @csrf
            <input type="hidden" name="alasan" id="inputAlasanHapusPost" value="Melanggar aturan lain">

            <!-- Alasan Penghapusan -->
            <h3 class="forum-modal-section-title">Alasan penghapusan</h3>
            <div class="forum-modal-pills">
                <button type="button" class="forum-modal-pill" onclick="selectModalPill(this, 'inputAlasanHapusPost', 'Perundungan')">Perundungan</button>
                <button type="button" class="forum-modal-pill" onclick="selectModalPill(this, 'inputAlasanHapusPost', 'Ancaman')">Ancaman</button>
                <button type="button" class="forum-modal-pill" onclick="selectModalPill(this, 'inputAlasanHapusPost', 'Spam')">Spam</button>
                <button type="button" class="forum-modal-pill is-selected" onclick="selectModalPill(this, 'inputAlasanHapusPost', 'Melanggar aturan lain')">Melanggar aturan lain</button>
            </div>

            <!-- Tindakan Tambahan -->
            <h3 class="forum-modal-section-title">Tindakan tambahan</h3>
            <div class="forum-modal-pills">
                <label class="forum-checkbox-pill">
                    <input type="checkbox" name="peringatkan" value="1" id="checkPeringatkanPost">
                    <span>Peringatkan akun</span>
                </label>
                <label class="forum-checkbox-pill">
                    <input type="checkbox" name="mute_24_jam" value="1" id="checkMutePost">
                    <span>Mute 24 jam</span>
                </label>
            </div>

            <!-- Footer -->
            <div class="forum-modal-footer">
                <p class="forum-modal-footer__note">
                    Disimpan di arsip moderasi dan bisa dipulihkan.
                </p>
                <div class="forum-modal-footer__actions">
                    <button type="button" class="btn-modal-cancel" onclick="closeHapusPostModal()">Batal</button>
                    <button type="submit" class="btn-modal-danger">Hapus Postingan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const antreanData = @json($antreanLaporan ?? []);
    const kontenData = @json($semuaKonten ?? []);
    const dibatasiData = @json($penggunaDibatasi ?? []);

    const routeTemplates = {
        hapus: @json(route('admin.forum.posts.hapus', ['id' => '__ID__'])),
        pulihkan: @json(route('admin.forum.posts.pulihkan', ['id' => '__ID__'])),
        tindak: @json(route('admin.forum.laporan.tindak', ['id' => '__ID__'])),
        tolak: @json(route('admin.forum.laporan.tolak', ['id' => '__ID__'])),
        sanksiStore: @json(route('admin.forum.sanksi.store')),
        sanksiCabut: @json(route('admin.forum.sanksi.cabut', ['id' => '__ID__'])),
    };

    function buildUrl(template, id) {
        return template.replace('__ID__', id);
    }

    let currentSelectedItem = antreanData.length > 0 ? antreanData[0] : null;

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

    function openHapusKomentarById(id) {
        const item = kontenData.find(x => x.id == id);
        if (item) openHapusKomentarModalWithData(item.id_post, item.penulis, item.waktu, item.isi_full || item.isi);
    }

    function openKelolaById(id, action) {
        const item = dibatasiData.find(x => x.id == id);
        if (item) openKelolaPembatasanModal(item, action);
    }

    // Filter Antrean Laporan (Semua, Perundungan, Ancaman, Berisiko, Spam)
    function filterQueue(btn, category) {
        document.querySelectorAll('.forum-chip-btn').forEach(b => b.classList.remove('is-active'));
        btn.classList.add('is-active');

        document.querySelectorAll('.forum-queue-card').forEach(card => {
            if (category === 'semua' || card.getAttribute('data-kategori') === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Pemilihan item antrean untuk update panel kanan dan action form
    function selectQueueItem(data, element) {
        document.querySelectorAll('.forum-queue-card').forEach(c => c.classList.remove('is-selected'));
        if (element) {
            element.classList.add('is-selected');
        }

        currentSelectedItem = data;

        document.getElementById('detailTitle').textContent = data.jenis === 'Komentar' ? 'Komentar Dilaporkan' : 'Postingan Dilaporkan';
        document.getElementById('detailSubtitle').textContent = data.sub_laporan || 'Menunggu tindakan';
        document.getElementById('detailHistoryNote').textContent = data.riwayat || '';

        if (data.post_konteks) {
            document.getElementById('postAuthor').textContent = data.post_konteks.penulis;
            document.getElementById('postMeta').textContent = data.post_konteks.waktu + ' - ' + data.post_konteks.komunitas;
            document.getElementById('postContent').textContent = data.post_konteks.isi;
            document.getElementById('detailPostBox').style.display = 'block';
        }

        const btnHapus = document.getElementById('btnHapusAction');
        const btnPulihkan = document.getElementById('btnPulihkanAction');
        if (data.komentar_konteks) {
            document.getElementById('commentAuthor').textContent = data.komentar_konteks.penulis;
            document.getElementById('commentMeta').textContent = data.komentar_konteks.waktu + ' Membalas ' + data.komentar_konteks.membalas;
            document.getElementById('commentContent').textContent = data.komentar_konteks.isi;
            document.getElementById('detailCommentBox').style.display = 'block';
            btnHapus.textContent = 'HAPUS KOMENTAR';
        } else {
            document.getElementById('detailCommentBox').style.display = 'none';
            btnHapus.textContent = 'HAPUS POSTINGAN';
        }

        document.getElementById('formTolakLaporan').action = buildUrl(routeTemplates.tolak, data.id_post);
        document.getElementById('formTindakLaporan').action = buildUrl(routeTemplates.tindak, data.id_post);
        document.getElementById('formPulihkanAntrean').action = buildUrl(routeTemplates.pulihkan, data.id_post);

        const isMenunggu = data.status_laporan === 'menunggu';
        document.getElementById('btnTolakLaporan').style.display = isMenunggu ? '' : 'none';
        document.getElementById('btnTindakLaporan').style.display = isMenunggu ? '' : 'none';

        const isDihapus = data.status_konten === 'dihapus';
        btnHapus.style.display = isDihapus ? 'none' : '';
        btnPulihkan.style.display = isDihapus ? '' : 'none';

        document.getElementById('muteInputUser').value = data.id_user;
        document.getElementById('muteInputLaporan').value = data.id_laporan || '';
        document.getElementById('muteInputAlasan').value = data.kategori || 'Pelanggaran forum';

        document.getElementById('peringatanInputUser').value = data.id_user;
        document.getElementById('peringatanInputLaporan').value = data.id_laporan || '';
        document.getElementById('peringatanInputAlasan').value = data.kategori || 'Pelanggaran forum';
    }

    function openHapusAntreanModal() {
        if (!currentSelectedItem) return;
        if (currentSelectedItem.jenis === 'Komentar') {
            openHapusKomentarModalWithData(
                currentSelectedItem.id_post,
                currentSelectedItem.penulis,
                currentSelectedItem.waktu,
                currentSelectedItem.isi
            );
        } else {
            openHapusPostModal({
                id_post: currentSelectedItem.id_post,
                penulis: currentSelectedItem.penulis,
                waktu: currentSelectedItem.waktu,
                isi: currentSelectedItem.isi,
                komentar_count: 0
            });
        }
    }

    function openHapusKomentarModalWithData(idPost, author, time, body) {
        document.getElementById('formHapusKomentar').action = buildUrl(routeTemplates.hapus, idPost);
        document.getElementById('modalKomentarAuthor').textContent = author;
        document.getElementById('modalKomentarTime').textContent = '· ' + time;
        document.getElementById('modalKomentarBody').textContent = body;
        document.getElementById('modalHapusKomentar').style.display = 'flex';
    }

    function closeHapusKomentarModal() {
        document.getElementById('modalHapusKomentar').style.display = 'none';
    }

    // Modal Detail Postingan
    let activePostDetail = null;

    function openDetailPostModal(post) {
        activePostDetail = post;
        document.getElementById('modalPostAuthor').textContent = post.alias || post.penulis;
        document.getElementById('modalPostMeta').textContent = post.waktu + ' · ' + post.komunitas;
        document.getElementById('modalPostStatus').textContent = post.status;
        document.getElementById('modalPostContent').textContent = post.isi_full || post.isi;
        document.getElementById('modalPostStats').textContent = (post.dukungan ?? 0) + ' dukungan · ' + (post.komentar_count ?? 0) + ' komentar';

        const group = document.getElementById('modalPostCommentsGroup');
        group.innerHTML = '';

        const comments = post.komentar_list || [];
        if (comments.length === 0) {
            const emptyNote = document.createElement('p');
            emptyNote.style.fontSize = '13px';
            emptyNote.style.color = '#6B7280';
            emptyNote.textContent = 'Belum ada komentar pada postingan ini.';
            group.appendChild(emptyNote);
        } else {
            comments.forEach(comment => {
                const card = document.createElement('div');
                card.className = 'forum-comment-card' + (comment.is_flagged ? ' forum-comment-card--flagged' : '');

                const top = document.createElement('div');
                top.className = 'forum-comment-card__top';

                const authorSpan = document.createElement('span');
                authorSpan.className = 'forum-comment-card__author';
                const strong = document.createElement('strong');
                strong.textContent = comment.penulis;
                const timeSpan = document.createElement('span');
                timeSpan.textContent = ' · ' + comment.waktu;
                authorSpan.appendChild(strong);
                authorSpan.appendChild(timeSpan);
                top.appendChild(authorSpan);

                if (comment.is_flagged) {
                    const badge = document.createElement('span');
                    badge.className = 'forum-badge-pill forum-badge-pill--dilaporkan';
                    badge.textContent = 'Dilaporkan ' + comment.laporan;
                    top.appendChild(badge);
                }

                const body = document.createElement('p');
                body.className = 'forum-comment-card__body';
                body.textContent = comment.isi;

                const actions = document.createElement('div');
                actions.className = 'forum-comment-card__actions';
                const btnDel = document.createElement('button');
                btnDel.type = 'button';
                btnDel.className = 'forum-comment-action-btn';
                btnDel.textContent = 'Hapus komentar';
                btnDel.onclick = () => {
                    closeDetailPostModal();
                    openHapusKomentarModalWithData(comment.id_post, comment.penulis, comment.waktu, comment.isi);
                };
                actions.appendChild(btnDel);

                card.appendChild(top);
                card.appendChild(body);
                card.appendChild(actions);
                group.appendChild(card);
            });
        }

        document.getElementById('modalDetailPost').style.display = 'flex';
    }

    function closeDetailPostModal() {
        document.getElementById('modalDetailPost').style.display = 'none';
    }

    function switchToHapusPostModal() {
        closeDetailPostModal();
        if (activePostDetail) {
            openHapusPostModal(activePostDetail);
        }
    }

    // Modal Hapus Postingan
    function openHapusPostModal(post) {
        document.getElementById('formHapusPost').action = buildUrl(routeTemplates.hapus, post.id_post || post.id);
        document.getElementById('modalHapusPostAuthor').textContent = post.alias || post.penulis || 'Anonim';
        document.getElementById('modalHapusPostMeta').textContent = '· ' + (post.waktu || '-');
        document.getElementById('modalHapusPostBody').textContent = post.isi_full || post.isi;
        document.getElementById('modalHapusPostWarning').textContent = (post.komentar_count ?? 0) + ' komentar pada postingan ini ikut terdampak saat postingan dihapus.';

        document.getElementById('modalHapusPost').style.display = 'flex';
    }

    function closeHapusPostModal() {
        document.getElementById('modalHapusPost').style.display = 'none';
    }

    // Pills interaktif alasan penghapusan
    function selectModalPill(btn, hiddenInputId, category) {
        const parent = btn.parentElement;
        parent.querySelectorAll('.forum-modal-pill').forEach(p => p.classList.remove('is-selected'));
        btn.classList.add('is-selected');

        const hiddenInput = document.getElementById(hiddenInputId);
        if (hiddenInput) {
            hiddenInput.value = category;
        }
    }

    // Modal Kelola Pembatasan (Tab 3)
    let currentKelolaUser = null;

    function openKelolaPembatasanModal(user, action) {
        currentKelolaUser = user;
        document.getElementById('kelolaUserName').textContent = user.nama;
        document.getElementById('kelolaInputUserId').value = user.id_user;

        const statusBadge = document.getElementById('kelolaUserStatus');
        statusBadge.textContent = user.status;
        statusBadge.className = 'forum-badge-pill';
        if (user.status_type === 'mute') statusBadge.classList.add('forum-badge-pill--mute');
        else if (user.status_type === 'blocked') statusBadge.classList.add('forum-badge-pill--blocked');

        document.getElementById('kelolaUserSub').textContent = user.sub_info || `Alasan: ${user.alasan}`;
        document.getElementById('kelolaAlasanInput').value = user.alasan || 'Pelanggaran aturan forum';

        const pills = document.querySelectorAll('#kelolaTindakanPills .forum-modal-pill');
        let matchedPill = pills[1];
        pills.forEach(p => {
            p.classList.remove('is-selected');
            if (action && p.textContent.toLowerCase().includes(action.toLowerCase())) {
                matchedPill = p;
            }
        });
        selectKelolaTindakan(matchedPill, matchedPill.textContent.trim());

        document.getElementById('modalKelolaPembatasan').style.display = 'flex';
    }

    function closeKelolaPembatasanModal() {
        document.getElementById('modalKelolaPembatasan').style.display = 'none';
    }

    function selectKelolaTindakan(btn, actionName) {
        document.querySelectorAll('#kelolaTindakanPills .forum-modal-pill').forEach(p => p.classList.remove('is-selected'));
        btn.classList.add('is-selected');

        const form = document.getElementById('formKelolaPembatasan');
        const inputJenis = document.getElementById('kelolaInputJenis');
        const durasiWrapper = document.getElementById('kelolaDurasiWrapper');
        const durasiSelect = document.getElementById('kelolaDurasiSelect');
        const alasanWrapper = document.getElementById('kelolaAlasanWrapper');
        const alasanInput = document.getElementById('kelolaAlasanInput');
        const warningBox = document.getElementById('kelolaWarningBox');

        if (actionName === 'Cabut pembatasan' && currentKelolaUser) {
            form.action = buildUrl(routeTemplates.sanksiCabut, currentKelolaUser.id_sanksi);
            durasiWrapper.style.display = 'none';
            alasanWrapper.style.display = 'none';
            alasanInput.required = false;
            warningBox.style.display = 'none';
            return;
        }

        form.action = routeTemplates.sanksiStore;
        durasiWrapper.style.display = '';
        alasanWrapper.style.display = '';
        alasanInput.required = true;

        if (actionName === 'Blokir permanen') {
            inputJenis.value = 'blokir';
            durasiSelect.value = '';
            warningBox.style.display = 'block';
        } else if (actionName === 'Blokir 7 hari') {
            inputJenis.value = 'blokir';
            durasiSelect.value = '168';
            warningBox.style.display = 'none';
        } else {
            inputJenis.value = 'mute';
            durasiSelect.value = '24';
            warningBox.style.display = 'none';
        }
    }

    // Filter Table Pengguna Dibatasi
    function filterDibatasiTable() {
        const search = document.getElementById('searchDibatasiInput').value.toLowerCase();
        const status = document.getElementById('filterDibatasiStatus').value.toLowerCase();

        document.querySelectorAll('#tableDibatasi tbody tr.forum-dibatasi-row').forEach(row => {
            const rowNama = (row.getAttribute('data-nama') || '').toLowerCase();
            const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();

            const matchSearch = rowNama.includes(search) || row.textContent.toLowerCase().includes(search);
            const matchStatus = !status || rowStatus.includes(status);

            row.style.display = (matchSearch && matchStatus) ? '' : 'none';
        });
    }
</script>
@endpush
