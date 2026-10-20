@extends('admin.layouts.base')

@section('title', 'Rehat — CURHATIN Admin')
@section('meta_description', 'Pemantauan kesehatan mental mahasiswa dan kesiagapan psikolog secara berkala')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav__item">
        <span class="material-icons-round">dashboard</span>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.assesmen.index') }}" class="sidebar-nav__item">
        <span class="material-icons-round">assignment</span>
        <span>Asesmen DASS-21</span>
    </a>
    <a href="{{ route('admin.rehat.index') }}" class="sidebar-nav__item active">
        <span class="material-icons-round">crop_square</span>
        <span>Rehat</span>
    </a>
    <a href="#" class="sidebar-nav__item">
        <span class="material-icons-round">groups</span>
        <span>Forum Anonim</span>
    </a>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/rehat.css') }}">
@endpush

@section('content')
<div class="rehat-wrapper">

    <!-- Header Section -->
    <div class="rehat-header">
        <div>
            <h1 class="rehat-header__title">REHAT</h1>
            <p class="rehat-header__subtitle">
                Pemantauan kesehatan mental mahasiswa dan kesiagapan psikolog secara berkala
            </p>
        </div>
        <div>
            <button class="btn-unggah" id="btnUnggahKonten">
                <span class="material-icons-round">add</span>
                <span>UNGGAH</span>
            </button>
        </div>
    </div>

    <!-- Divider -->
    <hr class="rehat-divider">

    <!-- Tabs & Search Row -->
    <div class="rehat-nav-row">
        <div class="rehat-tabs" role="tablist">
            <a href="{{ route('admin.rehat.index', ['tab' => 'artikel']) }}" class="rehat-tab {{ $tab === 'artikel' ? 'active' : '' }}">
                Artikel & Tips
            </a>
            <a href="{{ route('admin.rehat.index', ['tab' => 'audio']) }}" class="rehat-tab {{ $tab === 'audio' ? 'active' : '' }}">
                Musik/Audio
            </a>
            <a href="{{ route('admin.rehat.index', ['tab' => 'video']) }}" class="rehat-tab {{ $tab === 'video' ? 'active' : '' }}">
                Video
            </a>
        </div>

        <div class="rehat-search">
            <span class="material-icons-round rehat-search__icon">search</span>
            <input type="text" class="rehat-search__input" placeholder="Cari konten..." id="searchRehat">
        </div>
    </div>

    <!-- Outer Content Container -->
    <div class="rehat-content-container">

        @foreach($kontenList as $item)
            <div class="rehat-card">
                <!-- Thumbnail -->
                <img src="{{ asset($item['gambar']) }}" alt="{{ $item['judul'] }}" class="rehat-card__thumb">

                <!-- Body Content -->
                <div class="rehat-card__body">
                    <h3 class="rehat-card__title">{{ $item['judul'] }}</h3>
                    <p class="rehat-card__desc">{{ $item['deskripsi'] }}</p>

                    <div class="rehat-card__meta">
                        <span>{{ $item['penulis'] }}</span>
                        <span>{{ $item['views'] }}</span>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="rehat-card__badge {{ $item['status'] === 'Dipublikasi' ? 'rehat-card__badge--published' : 'rehat-card__badge--draft' }}">
                    {{ $item['status'] }}
                </div>

                <!-- Actions -->
                <div class="rehat-card__actions">
                    @if($item['status'] === 'Draf')
                        <button class="rehat-btn rehat-btn--terbitkan" onclick="alert('Konten berhasil diterbitkan!')">
                            TERBITKAN
                        </button>
                    @endif
                    <button class="rehat-btn rehat-btn--edit" onclick="alert('Fitur edit konten: {{ $item['judul'] }}')">
                        EDIT
                    </button>
                    <button class="rehat-btn rehat-btn--hapus" onclick="confirm('Apakah Anda yakin ingin menghapus konten ini?')">
                        HAPUS
                    </button>
                </div>
            </div>
        @endforeach

    </div>

</div>
@endsection
