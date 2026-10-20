@extends('admin.layouts.base')

@section('title', ($view === 'detail' ? $jurusan . ' — ' : 'Dashboard — ') . 'CURHATIN')
@section('meta_description', 'Pemantauan kesehatan mental mahasiswa dan kesiapan psikolog secara berkala')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav__item active">
        <span class="material-icons-round">dashboard</span>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.assesmen.index') }}" class="sidebar-nav__item">
        <span class="material-icons-round">assignment</span>
        <span>Asesmen DASS-21</span>
    </a>
    <a href="{{ route('admin.rehat.index') }}" class="sidebar-nav__item">
        <span class="material-icons-round">crop_square</span>
        <span>Rehat</span>
    </a>
    <a href="#" class="sidebar-nav__item">
        <span class="material-icons-round">groups</span>
        <span>Forum Anonim</span>
    </a>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endpush

@section('content')
<div class="dashboard-wrapper">

    @if($view === 'detail')
        {{-- =====================================================================
             TAMPILAN DETAIL JURUSAN (Sesuai Screenshot Desain Drilldown):
             Breadcrumb -> Teknik Informatika -> 3 Stat Cards -> DASS-21 -> Kasus Berat Antar Jurusan
             ===================================================================== --}}
        
        <!-- Breadcrumb -->
        <nav class="dashboard-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard', ['view' => 'overview']) }}">Dashboard</a>
            <span class="separator">›</span>
            <span>Distribusi Agregat DASS-21</span>
            <span class="separator">›</span>
            <span class="current">{{ $jurusan }}</span>
        </nav>

        <!-- Header -->
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-header__title">{{ $jurusan }}</h1>
                <p class="dashboard-header__subtitle">
                    Ringkasan kondisi kesehatan mental mahasiswa jurusan
                </p>
            </div>
            <div>
                <button class="btn-export" onclick="window.print()">
                    <span class="material-icons-round">download</span>
                    <span>Export Ringkasan</span>
                </button>
            </div>
        </div>

        <!-- 3 Stat Cards Sejajar -->
        <div class="stat-cards-grid">
            <div class="stat-card">
                <div class="stat-card__label">Kasus Berat</div>
                <div class="stat-card__value">142</div>
                <div class="stat-card__subtext stat-card__subtext--italic">12,5% dari 1.136 mahasiswa</div>
            </div>

            <div class="stat-card">
                <div class="stat-card__label">Tren</div>
                <div class="stat-card__value">+ 28%</div>
                <div class="stat-card__subtext stat-card__subtext--italic">Dipicu Tugas Akhir</div>
            </div>

            <div class="stat-card">
                <div class="stat-card__label">Kasus Berat</div>
                <div class="stat-card__value">142</div>
                <div class="stat-card__subtext stat-card__subtext--italic">12,5% dari 1.136 mahasiswa</div>
            </div>
        </div>

        <!-- Card: Distribusi Agregat DASS-21 -->
        <div class="section-card">
            <h2 class="section-card__title">Distribusi Agregat DASS-21</h2>

            <!-- Bar 1: Depresi -->
            <div class="dass-row">
                <div class="dass-row__labels">
                    <div class="dass-col dass-col--1">
                        <span class="dass-title">Depresi</span>
                        <span>55% Normal</span>
                    </div>
                    <div class="dass-col dass-col--2">
                        <span>20% Ringan</span>
                    </div>
                    <div class="dass-col dass-col--3">
                        <span>10% Sedang</span>
                    </div>
                    <div class="dass-col dass-col--4">
                        <span>15% Berat</span>
                    </div>
                    <div class="dass-col dass-col--5">
                        <span>5% Sangat Berat</span>
                    </div>
                </div>
                <div class="stacked-bar">
                    <div class="stacked-bar__seg seg--normal"></div>
                    <div class="stacked-bar__seg seg--ringan"></div>
                    <div class="stacked-bar__seg seg--sedang"></div>
                    <div class="stacked-bar__seg seg--berat"></div>
                    <div class="stacked-bar__seg seg--sangat-berat"></div>
                </div>
            </div>

            <!-- Bar 2: Kecemasan (Sesuai detail: 43% Normal, 20% Ringan, 17% Sedang, 15% Berat, 5% Sangat Berat) -->
            <div class="dass-row">
                <div class="dass-row__labels">
                    <div class="dass-col dass-col--1">
                        <span class="dass-title">Kecemasan</span>
                        <span>43% Normal</span>
                    </div>
                    <div class="dass-col dass-col--2">
                        <span>20% Ringan</span>
                    </div>
                    <div class="dass-col dass-col--3">
                        <span>17% Sedang</span>
                    </div>
                    <div class="dass-col dass-col--4">
                        <span>15% Berat</span>
                    </div>
                    <div class="dass-col dass-col--5">
                        <span>5% Sangat Berat</span>
                    </div>
                </div>
                <div class="stacked-bar">
                    <div class="stacked-bar__seg seg--normal"></div>
                    <div class="stacked-bar__seg seg--ringan"></div>
                    <div class="stacked-bar__seg seg--sedang"></div>
                    <div class="stacked-bar__seg seg--berat"></div>
                    <div class="stacked-bar__seg seg--sangat-berat"></div>
                </div>
            </div>

            <!-- Bar 3: Stress -->
            <div class="dass-row">
                <div class="dass-row__labels">
                    <div class="dass-col dass-col--1">
                        <span class="dass-title">Stress</span>
                        <span>55% Normal</span>
                    </div>
                    <div class="dass-col dass-col--2">
                        <span>20% Ringan</span>
                    </div>
                    <div class="dass-col dass-col--3">
                        <span>10% Sedang</span>
                    </div>
                    <div class="dass-col dass-col--4">
                        <span>15% Berat</span>
                    </div>
                    <div class="dass-col dass-col--5">
                        <span>5% Sangat Berat</span>
                    </div>
                </div>
                <div class="stacked-bar">
                    <div class="stacked-bar__seg seg--normal"></div>
                    <div class="stacked-bar__seg seg--ringan"></div>
                    <div class="stacked-bar__seg seg--sedang"></div>
                    <div class="stacked-bar__seg seg--berat"></div>
                    <div class="stacked-bar__seg seg--sangat-berat"></div>
                </div>
            </div>
        </div>

        <!-- Card: Kasus Berat Antar Jurusan (Sesuai Gambar 3) -->
        <div class="section-card">
            <h2 class="section-card__title">Kasus Berat Antar Jurusan</h2>

            <div class="jurusan-bars-container">
                <div class="jurusan-bar-row">
                    <div class="jurusan-bar-name">Teknik Informatika</div>
                    <div class="jurusan-bar-content">
                        <div class="jurusan-bar-fill" style="width: 80%; background-color: #700018;"></div>
                        <span class="jurusan-bar-percent">80%</span>
                    </div>
                </div>

                <div class="jurusan-bar-row">
                    <div class="jurusan-bar-name">Teknik Mesin</div>
                    <div class="jurusan-bar-content">
                        <div class="jurusan-bar-fill" style="width: 60%; background-color: #A8455A;"></div>
                        <span class="jurusan-bar-percent">60%</span>
                    </div>
                </div>

                <div class="jurusan-bar-row">
                    <div class="jurusan-bar-name">Teknik Elektro</div>
                    <div class="jurusan-bar-content">
                        <div class="jurusan-bar-fill" style="width: 70%; background-color: #700018;"></div>
                        <span class="jurusan-bar-percent">70%</span>
                    </div>
                </div>

                <div class="jurusan-bar-row">
                    <div class="jurusan-bar-name">Manajemen Bisnis</div>
                    <div class="jurusan-bar-content">
                        <div class="jurusan-bar-fill" style="width: 35%; background-color: #D9798C;"></div>
                        <span class="jurusan-bar-percent">35%</span>
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- =====================================================================
             TAMPILAN DASHBOARD UTAMA (PERSIS 100% SESUAI SCREENSHOT DESAIN TERBARU):
             Dahsboard -> 2 Card Stat -> Distribusi Agregat DASS-21 -> Tabel Jurusan
             ===================================================================== --}}
        
        <!-- Header -->
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-header__title">Dahsboard</h1>
                <p class="dashboard-header__subtitle">
                    Pemantauan kesehatan mental mahasiswa dan kesiagapan psikolog secara berkala
                </p>
            </div>
            <div>
                <button class="btn-export" onclick="window.print()">
                    <span class="material-icons-round">download</span>
                    <span>Export Ringkasan</span>
                </button>
            </div>
        </div>

        <!-- Dua Card Statistik Sejajar -->
        <div class="kpi-grid-two">
            <div class="stat-card">
                <div class="stat-card__label">TOTA PENGGUNA</div>
                <div class="stat-card__value">19 JUTA</div>
                <div class="stat-card__subtext stat-card__subtext--italic">5% Mode anonim</div>
            </div>

            <div class="stat-card">
                <div class="stat-card__label">SKRINING DASS-21 SELESAI</div>
                <div class="stat-card__value">19 JUTA</div>
                <div class="stat-card__subtext">Rata - rata: Stress Sedang</div>
            </div>
        </div>

        <!-- Card: Distribusi Agregat DASS-21 -->
        <div class="section-card">
            <h2 class="section-card__title">Distribusi Agregat DASS-21</h2>

            <!-- Bar 1: Depresi -->
            <div class="dass-row">
                <div class="dass-row__labels">
                    <div class="dass-col dass-col--1">
                        <span class="dass-title">Depresi</span>
                        <span>55% Normal</span>
                    </div>
                    <div class="dass-col dass-col--2">
                        <span>20% Ringan</span>
                    </div>
                    <div class="dass-col dass-col--3">
                        <span>10% Sedang</span>
                    </div>
                    <div class="dass-col dass-col--4">
                        <span>15% Berat</span>
                    </div>
                    <div class="dass-col dass-col--5">
                        <span>5% Sangat Berat</span>
                    </div>
                </div>
                <div class="stacked-bar">
                    <div class="stacked-bar__seg seg--normal"></div>
                    <div class="stacked-bar__seg seg--ringan"></div>
                    <div class="stacked-bar__seg seg--sedang"></div>
                    <div class="stacked-bar__seg seg--berat"></div>
                    <div class="stacked-bar__seg seg--sangat-berat"></div>
                </div>
            </div>

            <!-- Bar 2: Kecemasan -->
            <div class="dass-row">
                <div class="dass-row__labels">
                    <div class="dass-col dass-col--1">
                        <span class="dass-title">Kecemasan</span>
                        <span>55% Normal</span>
                    </div>
                    <div class="dass-col dass-col--2">
                        <span>20% Ringan</span>
                    </div>
                    <div class="dass-col dass-col--3">
                        <span>10% Sedang</span>
                    </div>
                    <div class="dass-col dass-col--4">
                        <span>15% Berat</span>
                    </div>
                    <div class="dass-col dass-col--5">
                        <span>5% Sangat Berat</span>
                    </div>
                </div>
                <div class="stacked-bar">
                    <div class="stacked-bar__seg seg--normal"></div>
                    <div class="stacked-bar__seg seg--ringan"></div>
                    <div class="stacked-bar__seg seg--sedang"></div>
                    <div class="stacked-bar__seg seg--berat"></div>
                    <div class="stacked-bar__seg seg--sangat-berat"></div>
                </div>
            </div>

            <!-- Bar 3: Stress -->
            <div class="dass-row">
                <div class="dass-row__labels">
                    <div class="dass-col dass-col--1">
                        <span class="dass-title">Stress</span>
                        <span>55% Normal</span>
                    </div>
                    <div class="dass-col dass-col--2">
                        <span>20% Ringan</span>
                    </div>
                    <div class="dass-col dass-col--3">
                        <span>10% Sedang</span>
                    </div>
                    <div class="dass-col dass-col--4">
                        <span>15% Berat</span>
                    </div>
                    <div class="dass-col dass-col--5">
                        <span>5% Sangat Berat</span>
                    </div>
                </div>
                <div class="stacked-bar">
                    <div class="stacked-bar__seg seg--normal"></div>
                    <div class="stacked-bar__seg seg--ringan"></div>
                    <div class="stacked-bar__seg seg--sedang"></div>
                    <div class="stacked-bar__seg seg--berat"></div>
                    <div class="stacked-bar__seg seg--sangat-berat"></div>
                </div>
            </div>
        </div>

        <!-- Card: Distribusi Agregat DASS-21 (Tabel 3 Jurusan Sesuai Screenshot) -->
        <div class="section-card">
            <h2 class="section-card__title">Distribusi Agregat DASS-21</h2>

            <div class="table-container">
                <table class="curhatin-table">
                    <thead>
                        <tr>
                            <th style="width: 38%;">JURUSAN</th>
                            <th style="width: 25%;">KASUS BERAT</th>
                            <th style="width: 21%;">TREN</th>
                            <th style="width: 16%;">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <a href="{{ route('admin.dashboard', ['view' => 'detail', 'jurusan' => 'Teknik Informatika']) }}" class="jurusan-name-link">
                                    Teknik Informatika
                                </a>
                            </td>
                            <td class="kasus-value">142 (12.5%)</td>
                            <td>
                                <span class="trend-text--danger">+28% Tugas Akhir</span>
                            </td>
                            <td>
                                <span class="status-badge status-badge--perhatian">Perhatian Khusus</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="{{ route('admin.dashboard', ['view' => 'detail', 'jurusan' => 'Manajemen Bisnis']) }}" class="jurusan-name-link">
                                    Manajemen Bisnis
                                </a>
                            </td>
                            <td class="kasus-value">98 (6.5%)</td>
                            <td>
                                <span class="trend-text--neutral">+14% (Stase)</span>
                            </td>
                            <td>
                                <span class="status-badge status-badge--waspada">Waspada</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="{{ route('admin.dashboard', ['view' => 'detail', 'jurusan' => 'Teknik Mesin']) }}" class="jurusan-name-link">
                                    Teknik Mesin
                                </a>
                            </td>
                            <td class="kasus-value">84 (5.5%)</td>
                            <td>
                                <span class="trend-text--neutral">Stabil</span>
                            </td>
                            <td>
                                <span class="status-badge status-badge--terkendali">Terkendali</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
