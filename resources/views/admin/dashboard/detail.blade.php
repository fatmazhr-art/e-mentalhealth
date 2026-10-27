@extends('admin.layouts.base')

@section('title', 'Detail ' . $kategori . ' — CURHATIN')
@section('meta_description', 'Sebaran tingkat ' . strtolower($kategori) . ' mahasiswa yang menyelesaikan skrining')

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
        @include('admin.partials.icon-rehat')
        <span>Rehat</span>
    </a>
    <a href="#" class="sidebar-nav__item">
        <span class="material-icons-round">groups</span>
        <span>Forum Anonim</span>
    </a>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/pages/detail-dashboard.css') }}">
@endpush

@section('content')
<div class="detail-dashboard-wrapper">

    <!-- 1. Breadcrumb Kecil -->
    <nav class="detail-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="separator">›</span>
        <a href="{{ route('admin.dashboard') }}">Distribusi Agregat DASS-21</a>
        <span class="separator">›</span>
        <span class="current">{{ $kategori }}</span>
    </nav>

    <!-- 2. Judul Besar & Subjudul + Tombol Export Ringkasan -->
    <div class="detail-header">
        <div>
            <h1 class="detail-header__title">Detail {{ $kategori }}</h1>
            <p class="detail-header__subtitle">
                Sebaran tingkat {{ strtolower($kategori) }} mahasiswa yang menyelesaikan skrining
            </p>
        </div>
        <div>
            <button class="btn-export" onclick="window.print()">
                <span class="material-icons-round">download</span>
                <span>Export Ringkasan</span>
            </button>
        </div>
    </div>

    <!-- 3. Baris Kontrol: Kategori Pills (Kiri) & Periode Toggle (Kanan) -->
    <div class="detail-controls">
        <div class="category-pills">
            <a href="{{ route('admin.dashboard.detail', 'depresi') }}"
               class="category-pill {{ $kategoriSlug === 'depresi' ? 'active' : '' }}">
                Depresi
            </a>
            <a href="{{ route('admin.dashboard.detail', 'kecemasan') }}"
               class="category-pill {{ $kategoriSlug === 'kecemasan' ? 'active' : '' }}">
                Kecemasan
            </a>
            <a href="{{ route('admin.dashboard.detail', 'stress') }}"
               class="category-pill {{ $kategoriSlug === 'stress' ? 'active' : '' }}">
                Stress
            </a>
        </div>

        <div class="period-toggle" role="group" aria-label="Pilih Periode">
            <button type="button"
                    class="period-btn {{ $periode === '1 bulan' ? 'active' : '' }}"
                    data-period="1 bulan">
                1 bulan
            </button>
            <button type="button"
                    class="period-btn {{ $periode === '7 Hari' ? 'active' : '' }}"
                    data-period="7 Hari">
                7 Hari
            </button>
        </div>
    </div>

    {{-- Placeholder untuk Tahap 2: Kartu Distribusi (bar + tabel) --}}

    {{-- Placeholder untuk Tahap 3 & 4: Kartu Tren & Penyebab Utama --}}

</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/pages/detail-dashboard.js') }}"></script>
@endpush
