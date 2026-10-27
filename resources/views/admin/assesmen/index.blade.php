@extends('admin.layouts.base')

{{-- 
    KEAMANAN & PRIVASI:
    // TODO: Halaman ini menampilkan data kesehatan mental mahasiswa yang bersifat rahasia.
    // TODO: Akses wajib dibatasi hanya untuk admin atau psikolog kampus yang berwenang (Role-Based Access Control / Permission).
--}}

@section('title', 'ASSESMEN MANDIRI DASS-21 — CURHATIN')
@section('meta_description', 'Pemantauan kesehatan mental mahasiswa dan kesiagaan psikolog secara berkala')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav__item">
        <span class="material-icons-round">dashboard</span>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.assesmen.index') }}" class="sidebar-nav__item active">
        <span class="material-icons-round">assignment</span>
        <span>Asesmen DASS-21</span>
    </a>
    <a href="{{ route('admin.rehat.index') }}" class="sidebar-nav__item">
        @include('admin.partials.icon-rehat')
        <span>Rehat</span>
    </a>
    <a href="{{ route('admin.forum.index') }}" class="sidebar-nav__item">
        <span class="material-icons-round">groups</span>
        <span>Forum Anonim</span>
    </a>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/assesmen-target.css') }}">
@endpush

@section('content')
<div class="assesmen-page-wrap">

    <!-- 1. Header: Judul Besar + Deskripsi + Tombol + TAMBAH SOAL -->
    <div class="assesmen-header-row">
        <div class="assesmen-header-left">
            <h1 class="assesmen-main-title">ASSESMEN MANDIRI DASS-21</h1>
            <p class="assesmen-main-desc">
                @if($tab === 'konfigurasi')
                    Kelola soal asesmen dan soal penyebab yang tampil di aplikasi mahasiswa
                @else
                    Pemantauan kesehatan mental mahasiswa dan kesiagaan psikolog secara berkala
                @endif
            </p>
        </div>
        <div class="assesmen-header-right">
            {{-- 
                // Form tambah soal interaktif modal
            --}}
            <button type="button" class="btn-tambah-soal" id="btnTambahSoalTarget" onclick="openTambahSoalModal()">
                <span class="material-icons-round">add</span>
                <span>+ TAMBAH SOAL</span>
            </button>
        </div>
    </div>

    <!-- 2. Kartu Total Responden Lebar Penuh -->
    <div class="total-responden-card">
        <div class="total-responden-card__label">TOTA RESPONDEN</div>
        <div class="total-responden-card__value">
            @if($tab === 'konfigurasi')
                19 JUTA
            @else
                {{ $totalRespondenFormatted ?? '1.136' }}
            @endif
        </div>
    </div>

    <!-- 3. Navigasi Tab (Daftar Riwayat Asesmen vs Konfigurasi Soal) -->
    <div class="assesmen-tabs-bar" role="tablist">
        <a href="{{ route('admin.assesmen.index', ['tab' => 'riwayat']) }}" 
           class="assesmen-tab-item {{ $tab === 'riwayat' ? 'is-active' : '' }}" 
           role="tab" 
           aria-selected="{{ $tab === 'riwayat' ? 'true' : 'false' }}">
            Daftar Riwayat Asesmen
        </a>
        <a href="{{ route('admin.assesmen.index', ['tab' => 'konfigurasi']) }}" 
           class="assesmen-tab-item {{ $tab === 'konfigurasi' ? 'is-active' : '' }}" 
           role="tab" 
           aria-selected="{{ $tab === 'konfigurasi' ? 'true' : 'false' }}">
            Konfigurasi Soal
        </a>
    </div>

    <!-- 4. Area Konten Berdasarkan Tab Aktif -->
    <div class="assesmen-tab-content">
        @if($tab === 'konfigurasi')
            @include('admin.assesmen.partials.konfigurasi-soal')
        @else
            @include('admin.assesmen.partials.tabel-riwayat')
        @endif
    </div>

</div>

<!-- Modal Tambah Soal Sesuai Desain Target -->
@include('admin.assesmen.partials.modal-tambah-soal')

@endsection

@push('scripts')
    <script src="{{ asset('js/admin/assesmen.js') }}"></script>
@endpush
