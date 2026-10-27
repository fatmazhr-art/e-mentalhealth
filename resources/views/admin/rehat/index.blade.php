@extends('admin.layouts.base')

@section('title', 'REHAT — CURHATIN Admin')
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
        @include('admin.partials.icon-rehat')
        <span>Rehat</span>
    </a>
    <a href="{{ route('admin.forum.index') }}" class="sidebar-nav__item">
        <span class="material-icons-round">groups</span>
        <span>Forum Anonim</span>
    </a>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/rehat.css') }}">
@endpush

@section('content')
<div class="rehat-wrapper">

    <div class="rehat-header">
        <div>
            <h1 class="rehat-header__title">REHAT</h1>
            <p class="rehat-header__subtitle">
                Pemantauan kesehatan mental mahasiswa dan kesiagapan psikolog secara berkala
            </p>
        </div>
        <div>
            <button type="button" class="btn-unggah" id="btnUnggahKonten" onclick="document.getElementById('modalUnggahMedia').classList.add('is-open'); document.body.style.overflow = 'hidden';">
                <span class="material-icons-round">add</span>
                <span>UNGGAH</span>
            </button>
        </div>
    </div>

    <hr class="rehat-divider">

    <div class="rehat-nav-row">
        <div class="rehat-tabs" role="tablist">
            <a href="{{ route('admin.rehat.index', ['tab' => 'artikel']) }}"
               class="rehat-tab {{ $tab === 'artikel' ? 'active' : '' }}">
                Artikel & Tips
            </a>
            <a href="{{ route('admin.rehat.index', ['tab' => 'audio']) }}"
               class="rehat-tab {{ $tab === 'audio' ? 'active' : '' }}">
                Musik/Audio
            </a>
            <a href="{{ route('admin.rehat.index', ['tab' => 'video']) }}"
               class="rehat-tab {{ $tab === 'video' ? 'active' : '' }}">
                Video
            </a>
        </div>

        <div class="rehat-search">
            <span class="material-icons-round rehat-search__icon">search</span>
            <input type="text" class="rehat-search__input" id="searchRehat" autocomplete="off">
        </div>
    </div>

    <div class="rehat-content-container" id="rehatCardsContainer">
        @if($tab === 'artikel')
            @forelse($artikel as $item)
                @include('admin.rehat.partials.kartu-media', ['item' => $item, 'tipe' => 'artikel'])
            @empty
                <div class="rehat-empty">Belum ada artikel.</div>
            @endforelse
        @elseif($tab === 'audio')
            @forelse($audio as $item)
                @include('admin.rehat.partials.kartu-media', ['item' => $item, 'tipe' => 'audio'])
            @empty
                <div class="rehat-empty">Belum ada audio.</div>
            @endforelse
        @elseif($tab === 'video')
            @forelse($video as $item)
                @include('admin.rehat.partials.kartu-media', ['item' => $item, 'tipe' => 'video'])
            @empty
                <div class="rehat-empty">Belum ada video.</div>
            @endforelse
        @endif
    </div>

    @include('admin.rehat.partials.modal-unggah')

</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/rehat.js') }}"></script>
@endpush
