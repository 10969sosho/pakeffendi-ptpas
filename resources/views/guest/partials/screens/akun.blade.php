<div id="akun-screen" class="full-screen-page" style="display: none;">
    <div class="page-header">
        <button id="close-akun" class="back-btn" onclick="history.back()">
            <i class="fas fa-chevron-left"></i>
        </button>
        <h2 class="page-title">Akun Saya</h2>
    </div>

    <div class="akun-tabs">
        <div class="akun-tab active" data-tab="profil">Profil</div>
        <div class="akun-tab" data-tab="order">Riwayat</div>
    </div>

    <div class="akun-content">
        <!-- Profil Content -->
        <div id="akun-profil-content">
            <div class="info-group">
                <div class="info-row" role="button" data-action="editName">
                    <span class="label">Nama</span>
                    <div class="value-row">
                        <span data-profile-name>Guest</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
                <div class="info-row" role="button" data-action="editEmail">
                    <span class="label">Email</span>
                    <div class="value-row">
                        <span data-profile-email>-</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
                <div class="info-row" role="button" data-action="editPhone">
                    <span class="label">No. HP</span>
                    <div class="value-row">
                        <span data-profile-phone>-</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>

            <div class="info-group">
                <h3 class="info-title">Alamat Tersimpan</h3>
                <div class="info-row" role="button" data-action="editAddress">
                    <span class="label">Alamat Utama</span>
                    <div class="value-row">
                        <span data-profile-address class="truncate" style="max-width: 150px;">-</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Content -->
        <div id="akun-order-content" style="display: none;">
            <div class="empty-state" data-orders-empty style="display: none;">
                <i class="fas fa-shopping-bag"></i>
                <p>Belum ada riwayat pesanan</p>
            </div>
            <div class="order-history" data-order-history>
                <!-- Orders injected via JS -->
            </div>
        </div>
    </div>
</div>
