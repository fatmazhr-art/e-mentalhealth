{{-- 
    Modal Konfirmasi Logout (Keluar dari CURHATIN)
    Sesuai Desain Target:
    - Lingkaran icon logout krem #FCF6F0
    - Judul maroon bold #6E0010 "Keluar dari CURHATIN?"
    - Deskripsi teks
    - Tombol "Batal" (outline maroon) & "Ya, Keluar" (solid maroon)
--}}
<div class="logout-modal-backdrop" id="logoutModalBackdrop" role="dialog" aria-modal="true" aria-labelledby="logoutModalTitle" style="display: none;">
    <div class="logout-modal-card">
        <!-- Tombol X Pojok Kanan Atas -->
        <button type="button" class="logout-modal__close" onclick="closeLogoutModal()" aria-label="Tutup modal">
            &times;
        </button>

        <!-- Lingkaran Icon Logout Krem -->
        <div class="logout-modal__icon-wrap">
            <svg class="logout-modal__icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </div>

        <!-- Judul Bold Maroon -->
        <h2 class="logout-modal__title" id="logoutModalTitle">
            Keluar dari CURHATIN?
        </h2>

        <!-- Teks Deskripsi -->
        <p class="logout-modal__desc">
            Kamu akan keluar dari akun admin dan perlu login lagi untuk mengakses dashboard.
        </p>

        <!-- Baris Tombol Aksi: Batal & Ya, Keluar -->
        <div class="logout-modal__actions">
            <button type="button" class="logout-modal__btn logout-modal__btn--cancel" onclick="closeLogoutModal()">
                Batal
            </button>
            <form method="POST" action="{{ route('admin.logout') }}" style="display: inline-block; margin: 0;">
                @csrf
                <button type="submit" class="logout-modal__btn logout-modal__btn--confirm" id="btnConfirmLogout">
                    Ya, Keluar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openLogoutModal() {
        const modal = document.getElementById('logoutModalBackdrop');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logoutModalBackdrop');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    // Tutup saat klik di luar card atau tekan tombol ESC
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('logoutModalBackdrop');
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closeLogoutModal();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.style.display === 'flex') {
                    closeLogoutModal();
                }
            });
        }
    });
</script>
