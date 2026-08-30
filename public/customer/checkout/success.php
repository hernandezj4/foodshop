<?php
require_once __DIR__ . '/../../includes/customer_layout.php';
$order = $_SESSION['last_order'] ?? null;
if (!$order) { header('Location: '.APP_URL); exit; }
$waUrl = "https://wa.me/".WA_NUMBER."?text=".urlencode($order['wa']);
customerHeader();
?>
<div class="sp">
    <div class="sp-icon">✓</div>
    <h2>Order Placed!</h2>
    <p>Thank you for ordering</p>
    <div class="sp-card">
        <div class="lbl">Order Number</div>
        <div class="num">#<?= $order['number'] ?></div>
        <div class="lbl">Total</div>
        <div style="font-size:1.1rem;font-weight:700;color:var(--red)">Rp <?= number_format($order['total'],0,',','.') ?></div>
    </div>
    <p style="font-size:12px;color:var(--gray);margin:14px 0">Confirm via WhatsApp:</p>
    <a href="<?= $waUrl ?>" target="_blank" class="wa-link">📱 Send via WhatsApp</a>
    <br><br>
    <a href="<?= APP_URL ?>" class="btn btn-secondary btn-round btn-sm">← Home</a>
</div>

<?php customerFooter(); ?>
