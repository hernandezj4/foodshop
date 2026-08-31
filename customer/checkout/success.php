<?php
require_once __DIR__ . '/../../includes/customer_layout.php';
$order = $_SESSION['last_order'] ?? null;
if (!$order) { header('Location: '.APP_URL); exit; }
customerHeader();
?>
<div class="sp">
    <div class="sp-icon">✓</div>
    <h2>Pesanan Terkirim!</h2>
    <p>Terima kasih, pesanan kamu sudah dikirim ke admin via WhatsApp</p>
    <div class="sp-card">
        <div class="lbl">Nomor Pesanan</div>
        <div class="num">#<?= $order['number'] ?></div>
        <div class="lbl">Total</div>
        <div style="font-size:1.1rem;font-weight:700;color:var(--red)">Rp <?= number_format($order['total'],0,',','.') ?></div>
    </div>
    <p style="font-size:12px;color:var(--gray);margin:14px 0">Admin akan segera mengkonfirmasi pesanan kamu 😊</p>
    <a href="<?= APP_URL ?>" class="btn btn-primary btn-round">← Kembali ke Beranda</a>
</div>

<?php customerFooter(); ?>
