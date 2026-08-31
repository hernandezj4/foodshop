<?php
require_once __DIR__ . '/../../includes/customer_layout.php';
customerHeader();
?>

<div class="section">
    <h2 class="page-title" style="padding:0 0 14px">📍 Hubungi Kami</h2>

    <!-- Store Info -->
    <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;margin-bottom:14px;text-align:center">
        <div style="font-size:3rem;margin-bottom:8px">🍔</div>
        <h3 style="font-size:1.1rem;font-weight:800;margin-bottom:4px">FoodShop</h3>
        <p style="font-size:12px;color:var(--gray);margin-bottom:16px">Delicious food delivered fresh to your doorstep</p>
        
        <div style="text-align:left;max-width:320px;margin:0 auto">
            <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--gray-light)">
                <span style="font-size:1.2rem">📍</span>
                <div>
                    <div style="font-size:11px;color:var(--gray)">Alamat</div>
                    <div style="font-size:13px;font-weight:500">Jl. Raya No. 123, Jakarta</div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--gray-light)">
                <span style="font-size:1.2rem">📞</span>
                <div>
                    <div style="font-size:11px;color:var(--gray)">Telepon</div>
                    <div style="font-size:13px;font-weight:500">+62 857 8010 8474</div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--gray-light)">
                <span style="font-size:1.2rem">📧</span>
                <div>
                    <div style="font-size:11px;color:var(--gray)">Email</div>
                    <div style="font-size:13px;font-weight:500">hello@foodshop.com</div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:10px 0">
                <span style="font-size:1.2rem">⏰</span>
                <div>
                    <div style="font-size:11px;color:var(--gray)">Jam Buka</div>
                    <div style="font-size:13px;font-weight:500">Senin - Minggu: 09.00 - 22.00</div>
                </div>
            </div>
        </div>
    </div>

    <!-- How to Order -->
    <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;margin-bottom:14px">
        <h3 style="font-size:14px;font-weight:700;margin-bottom:14px;text-align:center">🛒 Cara Pesan</h3>
        <div style="display:flex;flex-direction:column;gap:14px">
            <div style="display:flex;gap:12px;align-items:flex-start">
                <div style="width:32px;height:32px;background:var(--red);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;flex-shrink:0">1</div>
                <div>
                    <div style="font-size:13px;font-weight:600;margin-bottom:2px">Pilih Menu</div>
                    <div style="font-size:11px;color:var(--gray)">Browse menu dan pilih makanan/minuman favoritmu</div>
                </div>
            </div>
            <div style="display:flex;gap:12px;align-items:flex-start">
                <div style="width:32px;height:32px;background:var(--red);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;flex-shrink:0">2</div>
                <div>
                    <div style="font-size:13px;font-weight:600;margin-bottom:2px">Tambah ke Cart</div>
                    <div style="font-size:11px;color:var(--gray)">Klik tombol + untuk menambahkan ke keranjang</div>
                </div>
            </div>
            <div style="display:flex;gap:12px;align-items:flex-start">
                <div style="width:32px;height:32px;background:var(--red);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;flex-shrink:0">3</div>
                <div>
                    <div style="font-size:13px;font-weight:600;margin-bottom:2px">Checkout</div>
                    <div style="font-size:11px;color:var(--gray)">Isi data diri dan konfirmasi pesanan</div>
                </div>
            </div>
            <div style="display:flex;gap:12px;align-items:flex-start">
                <div style="width:32px;height:32px;background:#38a169;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;flex-shrink:0">4</div>
                <div>
                    <div style="font-size:13px;font-weight:600;margin-bottom:2px">Selesai!</div>
                    <div style="font-size:11px;color:var(--gray)">Pesananmu akan dikonfirmasi dan segera diproses</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Info -->
    <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;text-align:center">
        <h3 style="font-size:14px;font-weight:700;margin-bottom:8px">💳 Pembayaran</h3>
        <p style="font-size:12px;color:var(--gray)">Pembayaran dilakukan secara tunai saat pesanan diterima (Cash on Delivery)</p>
    </div>
</div>

<?php customerFooter(); ?>
