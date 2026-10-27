@extends('admin.layouts.base')

@section('title', 'PROFIL ADMIN — CURHATIN')
@section('meta_description', 'Kelola informasi akun dan kata sandi admin CURHATIN')

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
    <a href="{{ route('admin.forum.index') }}" class="sidebar-nav__item">
        <span class="material-icons-round">groups</span>
        <span>Forum Anonim</span>
    </a>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/profile.css') }}">
@endpush

@section('content')
<div class="profile-page-wrap">

    <!-- 1. Header Halaman -->
    <div class="profile-header">
        <h1 class="profile-main-title">PROFIL ADMIN</h1>
        <p class="profile-main-desc">Kelola informasi akun dan kata sandi admin.</p>
    </div>

    <!-- Alert Notifikasi Flash -->
    @if(session('success'))
        <div style="background-color: #D4EDDA; border: 1px solid #C3E6CB; color: #155724; padding: 12px 18px; border-radius: 10px; margin-bottom: 24px; font-weight: 600; font-size: 13.5px; display: flex; align-items: center; gap: 8px;">
            <span class="material-icons-round" style="font-size: 18px;">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div style="background-color: #F8D7DA; border: 1px solid #F5C6CB; color: #721C24; padding: 12px 18px; border-radius: 10px; margin-bottom: 24px; font-weight: 500; font-size: 13.5px;">
            <div style="font-weight: 700; margin-bottom: 4px;">Terjadi kesalahan:</div>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 2. Grid Layout Profil (2 Kolom) -->
    <div class="profile-grid">

        <!-- Kolom Kiri: Foto Profil & Aksi Foto -->
        <div class="profile-card-left">
            <div class="profile-avatar-wrap">
                <img src="{{ $fotoAdmin }}" alt="{{ $namaAdmin }}" class="profile-avatar-img" id="profileAvatarImg">
            </div>

            <h2 class="profile-admin-name">{{ $namaAdmin }}</h2>
            <span class="profile-role-pill">{{ $peranAdmin }}</span>

            <div class="profile-photo-actions">
                <!-- Form upload foto tersembunyi -->
                <form id="formUploadFoto" action="{{ route('admin.profile.photo') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                    @csrf
                    <input type="file" name="foto" id="fotoInput" accept="image/*" onchange="document.getElementById('formUploadFoto').submit();">
                </form>

                <button type="button" class="btn-ubah-foto" onclick="document.getElementById('fotoInput').click();">
                    Ubah Foto
                </button>

                <!-- Form reset / hapus foto -->
                <form id="formHapusFoto" action="{{ route('admin.profile.photo.delete') }}" method="POST" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-hapus-foto" onclick="return confirm('Apakah Anda yakin ingin menghapus foto profil ini?');">
                        Hapus Foto
                    </button>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: 2 Card (Informasi Akun & Ubah Kata Sandi) -->
        <div class="profile-column-right">

            <!-- Card 1: Informasi Akun -->
            <div class="profile-card-form">
                <h3 class="profile-form-title">Informasi Akun</h3>

                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf

                    <div class="profile-form-grid">
                        <!-- Nama Lengkap -->
                        <div class="profile-form-group">
                            <label for="inputNama" class="profile-form-label">Nama Lengkap</label>
                            <input type="text" 
                                   id="inputNama" 
                                   name="nama" 
                                   class="profile-form-input" 
                                   value="{{ old('nama', $namaAdmin) }}" 
                                   required>
                        </div>

                        <!-- Email -->
                        <div class="profile-form-group">
                            <label for="inputEmail" class="profile-form-label">Email</label>
                            <input type="email" 
                                   id="inputEmail" 
                                   name="email" 
                                   class="profile-form-input" 
                                   value="{{ old('email', $emailAdmin) }}" 
                                   required>
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="profile-form-group">
                            <label for="inputTelepon" class="profile-form-label">Nomor Telepon</label>
                            <input type="text" 
                                   id="inputTelepon" 
                                   name="telepon" 
                                   class="profile-form-input" 
                                   value="{{ old('telepon', $teleponAdmin) }}">
                        </div>

                        <!-- Peran -->
                        <div class="profile-form-group">
                            <label for="inputPeran" class="profile-form-label">Peran</label>
                            <input type="text" 
                                   id="inputPeran" 
                                   class="profile-form-input" 
                                   value="{{ $peranAdmin }}" 
                                   readonly>
                        </div>
                    </div>

                    <div class="profile-form-actions">
                        <button type="submit" class="btn-profile-submit">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card 2: Ubah Kata Sandi -->
            <div class="profile-card-form">
                <h3 class="profile-form-title">Ubah Kata Sandi</h3>

                <form action="{{ route('admin.profile.password') }}" method="POST">
                    @csrf

                    <div class="profile-form-grid">
                        <!-- Kata sandi saat ini (Full width) -->
                        <div class="profile-form-group profile-form-group--full">
                            <label for="currentPassword" class="profile-form-label">Kata sandi saat ini</label>
                            <input type="password" 
                                   id="currentPassword" 
                                   name="current_password" 
                                   class="profile-form-input" 
                                   placeholder="Masukkan kata sandi saat ini" 
                                   required>
                        </div>

                        <!-- Kata sandi baru (Label disesuaikan dengan mock "Kata sandi saat ini" / "Kata sandi baru") -->
                        <div class="profile-form-group">
                            <label for="newPassword" class="profile-form-label">Kata sandi baru</label>
                            <input type="password" 
                                   id="newPassword" 
                                   name="password" 
                                   class="profile-form-input" 
                                   placeholder="Minimal 6 karakter" 
                                   required>
                        </div>

                        <!-- Konfirmasi sandi baru -->
                        <div class="profile-form-group">
                            <label for="confirmPassword" class="profile-form-label">Konfirmasi sandi baru</label>
                            <input type="password" 
                                   id="confirmPassword" 
                                   name="password_confirmation" 
                                   class="profile-form-input" 
                                   placeholder="Ulangi kata sandi baru" 
                                   required>
                        </div>
                    </div>

                    <div class="profile-form-actions">
                        <button type="submit" class="btn-profile-submit">
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection
