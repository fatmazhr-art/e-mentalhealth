@extends('admin.layouts.base')

@section('title', 'Dashboard — CURHATIN')
@section('meta_description', 'Pemantauan kesehatan mental mahasiswa dan kesiapan psikolog secara berkala')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav__item active">
        <span class="material-icons-round">dashboard</span>
        <span>Dashboard</span>
    </a>
    <a href="#" class="sidebar-nav__item">
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
<style>
    /* Specific styles for CURHATIN Dashboard matching screenshot mockup */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .dashboard-header__title {
        font-size: 38px;
        font-weight: 900;
        color: #171717;
        letter-spacing: -0.5px;
        line-height: 1.1;
        margin-bottom: 8px;
    }

    .dashboard-header__subtitle {
        font-size: 15px;
        font-weight: 500;
        color: #404040;
        max-width: 600px;
        line-height: 1.4;
    }

    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #7A0C1E;
        color: #FFFFFF;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(122, 12, 30, 0.25);
    }

    .btn-export:hover {
        background: #600816;
        color: #FFFFFF;
        transform: translateY(-1px);
    }

    .btn-export .material-icons-round {
        font-size: 18px;
    }

    /* ---- Two Top KPI Summary Cards ---- */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-bottom: 24px;
    }

    .kpi-card {
        background: #F8F3EC;
        border: 1.5px solid #E2D9CD;
        border-radius: 18px;
        padding: 24px 28px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    .kpi-card__title {
        font-size: 13px;
        font-weight: 800;
        color: #1F1F1F;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 8px;
    }

    .kpi-card__value {
        font-size: 26px;
        font-weight: 900;
        font-style: italic;
        color: #8B0E23;
        line-height: 1.2;
        margin-bottom: 12px;
        letter-spacing: 0.5px;
    }

    .kpi-card__subtext {
        font-size: 13px;
        font-style: italic;
        color: #333333;
        font-weight: 500;
    }

    /* ---- Main Section Card ---- */
    .section-card {
        background: #F8F3EC;
        border: 1.5px solid #E2D9CD;
        border-radius: 18px;
        padding: 24px 28px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    .section-card__title {
        font-size: 17px;
        font-weight: 800;
        color: #171717;
        margin-bottom: 20px;
    }

    /* ---- Multi-segmented Progress Bar ---- */
    .dass-row {
        margin-bottom: 22px;
    }

    .dass-row:last-child {
        margin-bottom: 4px;
    }

    .dass-row__header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 6px;
    }

    .dass-row__label {
        font-size: 15px;
        font-weight: 800;
        color: #171717;
    }

    .dass-row__legend {
        display: flex;
        gap: 20px;
        font-size: 11px;
        font-style: italic;
        font-weight: 600;
        color: #262626;
    }

    .stacked-bar {
        height: 18px;
        border-radius: 3px;
        display: flex;
        overflow: hidden;
        background: #D9D9D9;
        width: 100%;
    }

    .stacked-bar__seg {
        height: 100%;
        transition: width 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Colors matching the mockup */
    .seg--normal { background-color: #84CC16; }       /* Light green */
    .seg--ringan { background-color: #0052FF; }       /* Bright blue */
    .seg--sedang { background-color: #FF2A00; }       /* Red/Orange */
    .seg--berat  { background-color: #CC0000; }       /* Deep red */
    .seg--sangat-berat { background-color: #550714; } /* Dark maroon */

    /* ---- Jurusan Table ---- */
    .table-container {
        width: 100%;
        overflow-x: auto;
    }

    .curhatin-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .curhatin-table th {
        font-size: 12px;
        font-weight: 800;
        color: #1F1F1F;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 14px;
        border-bottom: 1px solid rgba(0,0,0,0.06);
    }

    .curhatin-table td {
        padding: 14px 14px;
        font-size: 13.5px;
        color: #1F1F1F;
        vertical-align: middle;
    }

    .curhatin-table tr:not(:last-child) td {
        border-bottom: 1px solid rgba(0,0,0,0.03);
    }

    .jurusan-name {
        font-weight: 800;
        color: #171717;
    }

    .kasus-value {
        font-weight: 600;
        color: #1F1F1F;
    }

    .trend-badge {
        font-weight: 600;
        font-size: 13px;
    }

    .trend-badge--danger {
        color: #DC2626;
    }

    .trend-badge--neutral {
        color: #1F1F1F;
    }

    /* Status Pills matching mockup */
    .status-pill {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        text-align: center;
        letter-spacing: 0.3px;
    }

    .status-pill--danger {
        background-color: #8B0E23;
        color: #FFFFFF;
    }

    .status-pill--warning {
        background-color: #F8D384;
        color: #92400E;
    }

    .status-pill--success {
        background-color: #52B76D;
        color: #FFFFFF;
    }

    @media (max-width: 768px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }

        .dass-row__header {
            flex-direction: column;
            gap: 4px;
        }

        .dass-row__legend {
            flex-wrap: wrap;
            gap: 8px 12px;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-wrapper">

    {{-- Header --}}
    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-header__title">Dashboard</h1>
            <p class="dashboard-header__subtitle">
                Pemantauan kesehatan mental mahasiswa dan kesiapan psikolog secara berkala
            </p>
        </div>
        <div>
            <button class="btn-export" onclick="window.print()">
                <span class="material-icons-round">download</span>
                <span>Export Ringkasan</span>
            </button>
        </div>
    </div>

    {{-- Flash Message if any --}}
    @if (session('success'))
        <div style="background: #F0FDF4; border: 1px solid #BBF7D0; color: #166534; padding: 12px 18px; border-radius: 10px; margin-bottom: 20px; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Row 1: KPI Summary Cards --}}
    <div class="kpi-grid">
        {{-- Card 1: Total Pengguna --}}
        <div class="kpi-card">
            <div class="kpi-card__title">TOTAL PENGGUNA</div>
            <div class="kpi-card__value">
                @if($totalPengguna > 1000000)
                    {{ round($totalPengguna / 1000000, 1) }} JUTA
                @elseif($totalPengguna > 0)
                    {{ number_format($totalPengguna) }} MAHASISWA
                @else
                    19 JUTA
                @endif
            </div>
            <div class="kpi-card__subtext">
                {{ $persenAnonim > 0 ? $persenAnonim . '% Mode anonim' : '5% Mode anonim' }}
            </div>
        </div>

        {{-- Card 2: Skrining Selesai --}}
        <div class="kpi-card">
            <div class="kpi-card__title">SKRINING DASS-21 SELESAI</div>
            <div class="kpi-card__value">
                @if($totalSkrining > 1000000)
                    {{ round($totalSkrining / 1000000, 1) }} JUTA
                @elseif($totalSkrining > 0)
                    {{ number_format($totalSkrining) }} KALI
                @else
                    19 JUTA
                @endif
            </div>
            <div class="kpi-card__subtext">
                {{ $labelRataRata !== 'Belum ada data' ? $labelRataRata : 'Rata - rata: Stress Sedang' }}
            </div>
        </div>
    </div>

    {{-- Row 2: Distribusi Agregat DASS-21 Multi-Bar --}}
    <div class="section-card">
        <h2 class="section-card__title">Distribusi Agregat DASS-21</h2>

        {{-- Depresi --}}
        <div class="dass-row">
            <div class="dass-row__header">
                <span class="dass-row__label">Depresi</span>
                <div class="dass-row__legend">
                    <span>{{ $distribusi['depresi']['normal'] ?? 55 }}% Normal</span>
                    <span>{{ $distribusi['depresi']['ringan'] ?? 20 }}% Ringan</span>
                    <span>{{ $distribusi['depresi']['sedang'] ?? 10 }}% Sedang</span>
                    <span>{{ $distribusi['depresi']['berat'] ?? 15 }}% Berat</span>
                    <span>{{ $distribusi['depresi']['sangat_berat'] ?? 5 }}% Sangat Berat</span>
                </div>
            </div>
            <div class="stacked-bar">
                <div class="stacked-bar__seg seg--normal" style="width: {{ $distribusi['depresi']['normal'] ?? 55 }}%;"></div>
                <div class="stacked-bar__seg seg--ringan" style="width: {{ $distribusi['depresi']['ringan'] ?? 20 }}%;"></div>
                <div class="stacked-bar__seg seg--sedang" style="width: {{ $distribusi['depresi']['sedang'] ?? 10 }}%;"></div>
                <div class="stacked-bar__seg seg--berat"  style="width: {{ $distribusi['depresi']['berat'] ?? 15 }}%;"></div>
                <div class="stacked-bar__seg seg--sangat-berat" style="width: {{ $distribusi['depresi']['sangat_berat'] ?? 5 }}%;"></div>
            </div>
        </div>

        {{-- Kecemasan --}}
        <div class="dass-row">
            <div class="dass-row__header">
                <span class="dass-row__label">Kecemasan</span>
                <div class="dass-row__legend">
                    <span>{{ $distribusi['kecemasan']['normal'] ?? 55 }}% Normal</span>
                    <span>{{ $distribusi['kecemasan']['ringan'] ?? 20 }}% Ringan</span>
                    <span>{{ $distribusi['kecemasan']['sedang'] ?? 10 }}% Sedang</span>
                    <span>{{ $distribusi['kecemasan']['berat'] ?? 15 }}% Berat</span>
                    <span>{{ $distribusi['kecemasan']['sangat_berat'] ?? 5 }}% Sangat Berat</span>
                </div>
            </div>
            <div class="stacked-bar">
                <div class="stacked-bar__seg seg--normal" style="width: {{ $distribusi['kecemasan']['normal'] ?? 55 }}%;"></div>
                <div class="stacked-bar__seg seg--ringan" style="width: {{ $distribusi['kecemasan']['ringan'] ?? 20 }}%;"></div>
                <div class="stacked-bar__seg seg--sedang" style="width: {{ $distribusi['kecemasan']['sedang'] ?? 10 }}%;"></div>
                <div class="stacked-bar__seg seg--berat"  style="width: {{ $distribusi['kecemasan']['berat'] ?? 15 }}%;"></div>
                <div class="stacked-bar__seg seg--sangat-berat" style="width: {{ $distribusi['kecemasan']['sangat_berat'] ?? 5 }}%;"></div>
            </div>
        </div>

        {{-- Stress --}}
        <div class="dass-row">
            <div class="dass-row__header">
                <span class="dass-row__label">Stress</span>
                <div class="dass-row__legend">
                    <span>{{ $distribusi['stress']['normal'] ?? 55 }}% Normal</span>
                    <span>{{ $distribusi['stress']['ringan'] ?? 20 }}% Ringan</span>
                    <span>{{ $distribusi['stress']['sedang'] ?? 10 }}% Sedang</span>
                    <span>{{ $distribusi['stress']['berat'] ?? 15 }}% Berat</span>
                    <span>{{ $distribusi['stress']['sangat_berat'] ?? 5 }}% Sangat Berat</span>
                </div>
            </div>
            <div class="stacked-bar">
                <div class="stacked-bar__seg seg--normal" style="width: {{ $distribusi['stress']['normal'] ?? 55 }}%;"></div>
                <div class="stacked-bar__seg seg--ringan" style="width: {{ $distribusi['stress']['ringan'] ?? 20 }}%;"></div>
                <div class="stacked-bar__seg seg--sedang" style="width: {{ $distribusi['stress']['sedang'] ?? 10 }}%;"></div>
                <div class="stacked-bar__seg seg--berat"  style="width: {{ $distribusi['stress']['berat'] ?? 15 }}%;"></div>
                <div class="stacked-bar__seg seg--sangat-berat" style="width: {{ $distribusi['stress']['sangat_berat'] ?? 5 }}%;"></div>
            </div>
        </div>
    </div>

    {{-- Row 3: Distribusi Agregat DASS-21 (Tabel Kasus per Jurusan) --}}
    <div class="section-card">
        <h2 class="section-card__title">Distribusi Agregat DASS-21</h2>

        <div class="table-container">
            <table class="curhatin-table">
                <thead>
                    <tr>
                        <th>JURUSAN</th>
                        <th>KASUS BERAT</th>
                        <th>TREN</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($tabelJurusan) && count($tabelJurusan) > 0)
                        @foreach($tabelJurusan as $item)
                            <tr>
                                <td class="jurusan-name">{{ $item->jurusan }}</td>
                                <td class="kasus-value">{{ $item->kasus_berat }} ({{ $item->persen_berat }}%)</td>
                                <td>
                                    <span class="trend-badge {{ str_contains($item->tren, '+') ? 'trend-badge--danger' : 'trend-badge--neutral' }}">
                                        {{ $item->tren }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-pill status-pill--{{ $item->status_class }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        {{-- Fallback ke tampilan default mockup --}}
                        <tr>
                            <td class="jurusan-name">Teknik Informatika</td>
                            <td class="kasus-value">142 (12.5%)</td>
                            <td>
                                <span class="trend-badge trend-badge--danger">+28% Tugas Akhir</span>
                            </td>
                            <td>
                                <span class="status-pill status-pill--danger">Perhatian Khusus</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="jurusan-name">Manajemen Bisnis</td>
                            <td class="kasus-value">98 (6.5%)</td>
                            <td>
                                <span class="trend-badge trend-badge--neutral">+14% (Stase)</span>
                            </td>
                            <td>
                                <span class="status-pill status-pill--warning">Waspada</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="jurusan-name">Teknik Mesin</td>
                            <td class="kasus-value">84 (5.5%)</td>
                            <td>
                                <span class="trend-badge trend-badge--neutral">Stabil</span>
                            </td>
                            <td>
                                <span class="status-pill status-pill--success">Terkendali</span>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
