<?php
require_once __DIR__ . '/../../includes/customer_layout.php';
$orders = $_SESSION['orders'] ?? [];
customerHeader();
?>

<div class="section">
    <h2 class="page-title" style="padding:0 0 14px">📋 My Orders</h2>

    <?php if (empty($orders)): ?>
    <div class="empty-state">
        <div class="icon">📋</div>
        <h3>Belum ada pesanan</h3>
        <p>Pesan makanan favoritmu sekarang!</p>
        <a href="<?= APP_URL ?>/customer/products/" class="btn btn-primary btn-round">Browse Menu</a>
    </div>
    <?php else: ?>
    <?php foreach (array_reverse($orders) as $order): ?>
    <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow);padding:16px;margin-bottom:12px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
            <div>
                <div style="font-weight:700;font-size:14px">#<?= $order['number'] ?></div>
                <div style="font-size:11px;color:var(--gray)"><?= date('d M Y, H:i', strtotime($order['date'])) ?></div>
            </div>
            <span class="sb <?= $order['status']==='pending'?'sb-pending':($order['status']==='confirmed'?'sb-confirmed':'sb-delivered') ?>">
                <?= ucfirst($order['status']) ?>
            </span>
        </div>
        <div style="border-top:1px solid var(--gray-light);padding-top:8px">
            <?php foreach ($order['items'] as $item): ?>
            <div style="display:flex;justify-content:space-between;padding:4px 0;font-size:12px">
                <span><?= sanitize($item['name']) ?> × <?= $item['quantity'] ?></span>
                <span style="font-weight:600">Rp <?= number_format($item['price']*$item['quantity'],0,',','.') ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="border-top:1px solid var(--gray-light);padding-top:8px;margin-top:6px;display:flex;justify-content:space-between;align-items:center">
            <div style="font-size:11px;color:var(--gray)">
                👤 <?= sanitize($order['name']) ?> · 🏫 <?= sanitize($order['kelas']) ?>
            </div>
            <div style="font-weight:800;color:var(--red)">Rp <?= number_format($order['total'],0,',','.') ?></div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php customerFooter(); ?>
