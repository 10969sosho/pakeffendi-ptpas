<div id="cart-screen" class="full-screen-page" style="display: none;">
    <div class="page-header">
        <button id="close-cart" class="back-btn" onclick="history.back()">
            <i class="fas fa-chevron-left"></i>
        </button>
        <h2 class="page-title">Keranjang</h2>
    </div>

    <div class="cart-content">
        <div class="cart-section-header">
            <span class="section-label">Order Review</span>
            <span class="user-code">Guest</span>
        </div>

        <div class="cart-empty empty-state" style="display: none;">
            <p>Keranjang masih kosong</p>
        </div>

        <div class="cart-item" data-cart-item style="display: none;">
            <div class="cart-item-img">
                <img data-cart-image src="https://placehold.co/100x100/orange/white?text=Item" alt="Item">
            </div>
            <div class="cart-item-details">
                <h4 class="item-name" data-cart-name></h4>
                <p class="item-price" data-cart-price></p>
                <div class="item-actions">
                    <div class="qty-control">
                        <button class="qty-btn minus"><i class="fas fa-minus"></i></button>
                        <input type="text" value="1" readonly class="qty-input">
                        <button class="qty-btn plus"><i class="fas fa-plus"></i></button>
                    </div>
                    <button class="trash-btn"><i class="fas fa-trash-alt"></i></button>
                </div>
            </div>
        </div>

        <div class="checkout-form" data-checkout-form style="display: none;">
            <h3 class="checkout-title">Data Pemesan</h3>

            <div class="form-grid">
                <label class="form-field">
                    <span class="form-label">Nama Lengkap</span>
                    <input type="text" placeholder="Nama lengkap" data-checkout-full-name>
                </label>
                <label class="form-field">
                    <span class="form-label">Email</span>
                    <input type="email" placeholder="nama@email.com" data-checkout-email>
                </label>
                <label class="form-field">
                    <span class="form-label">No. HP</span>
                    <input type="tel" placeholder="08xxxxxxxxxx" data-checkout-phone>
                </label>
                <label class="form-field form-field-full">
                    <span class="form-label">Alamat</span>
                    <textarea rows="3" placeholder="Alamat pengiriman (opsional)" data-checkout-address></textarea>
                </label>
                <label class="form-field form-field-full">
                    <span class="form-label">Catatan</span>
                    <textarea rows="3" placeholder="Catatan (opsional)" data-checkout-notes></textarea>
                </label>
            </div>

            <button class="submit-order-btn" type="button" data-submit-order-btn>Buat Order</button>
        </div>
    </div>

    <div class="cart-footer">
        <div class="cart-total">
            <span>Total</span>
            <span class="total-price">Rp. 0</span>
        </div>
        <button class="checkout-btn" data-checkout-btn>Checkout</button>
    </div>
</div>
