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
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endpush

@section('content')
<div class="dashboard-wrapper">

    
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

    
    @if (session('success'))
        <div style="background: #F0FDF4; border: 1px solid #BBF7D0; color: #166534; padding: 12px 18px; border-radius: 10px; margin-bottom: 20px; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    
    <div class="kpi-grid">
        @include('admin.partials.kpi-card', [
            'title'   => 'TOTAL PENGGUNA',
            'value'   => $totalPengguna > 1000000
                ? round($totalPengguna / 1000000, 1) . ' JUTA'
                : ($totalPengguna > 0 ? number_format($totalPengguna) . ' MAHASISWA' : '19 JUTA'),
            'subtext' => $persenAnonim > 0 ? $persenAnonim . '% Mode anonim' : '5% Mode anonim',
        ])

        @include('admin.partials.kpi-card', [
            'title'   => 'SKRINING DASS-21 SELESAI',
            'value'   => $totalSkrining > 1000000
                ? round($totalSkrining / 1000000, 1) . ' JUTA'
                : ($totalSkrining > 0 ? number_format($totalSkrining) . ' KALI' : '19 JUTA'),
            'subtext' => $labelRataRata !== 'Belum ada data' ? $labelRataRata : 'Rata - rata: Stress Sedang',
        ])
    </div>

    
    <div class="section-card">
        <h2 class="section-card__title">Distribusi Agregat DASS-21</h2>

        @include('admin.partials.dass-bar', [
            'label' => 'Depresi',
            'data'  => $distribusi['depresi'] ?? ['normal' => 55, 'ringan' => 20, 'sedang' => 10, 'berat' => 15, 'sangat_berat' => 5],
        ])

        @include('admin.partials.dass-bar', [
            'label' => 'Kecemasan',
            'data'  => $distribusi['kecemasan'] ?? ['normal' => 55, 'ringan' => 20, 'sedang' => 10, 'berat' => 15, 'sangat_berat' => 5],
        ])

        @include('admin.partials.dass-bar', [
            'label' => 'Stress',
            'data'  => $distribusi['stress'] ?? ['normal' => 55, 'ringan' => 20, 'sedang' => 10, 'berat' => 15, 'sangat_berat' => 5],
        ])
    </div>

    
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
                        <tr>
                            <td class="jurusan-name">Teknik Elektro</td>
                            <td class="kasus-value">62 (4.5%)</td>
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
