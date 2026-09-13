<?php
require_once __DIR__ . '/../../includes/customer_layout.php';

$combos = $pdo ? $pdo->query("SELECT * FROM combos WHERE is_active = 1 ORDER BY created_at DESC")->fetchAll() : [];
customerHeader();
?>

<div class="section">
    <div class="section-head">
        <h2 class="section-title">Combo Packages</h2>
    </div>
    <p style="font-size:12px;color:var(--gray);margin-bottom:16px">Hemat lebih banyak dengan paket combo! Pilih paket yang cocok buat kamu.</p>

    <?php if (empty($combos)): ?>
    <div class="empty-state">
        <div class="icon"><?= svgIcon('star', 48) ?></div>
        <h3>Belum ada combo</h3>
        <p>Paket combo akan segera hadir!</p>
    </div>
    <?php else: ?>
    <?php foreach ($combos as $combo): ?>
    <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;margin-bottom:14px">
        <div style="position:relative">
            <img src="<?= $combo['image'] ?>" alt="<?= sanitize($combo['name']) ?>" style="width:100%;height:180px;object-fit:cover" loading="lazy">
            <div style="position:absolute;top:12px;left:12px;background:var(--red);color:#fff;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700">COMBO</div>
        </div>
        <div style="padding:16px">
            <h3 style="font-size:16px;font-weight:700;margin-bottom:4px"><?= sanitize($combo['name']) ?></h3>
            <p style="font-size:12px;color:var(--gray);margin-bottom:10px"><?= sanitize($combo['description']) ?></p>
            <div style="background:var(--beige);border-radius:10px;padding:10px 12px;margin-bottom:12px">
                <div style="font-size:11px;font-weight:600;color:var(--dark);margin-bottom:4px">Isi Paket:</div>
                <div style="font-size:11px;color:var(--gray);line-height:1.6"><?= nl2br(sanitize($combo['item_details'])) ?></div>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center">
                <div>
                    <span style="font-size:11px;color:var(--gray);text-decoration:line-through">Rp <?= number_format($combo['price'] + 8000, 0, ',', '.') ?></span>
                    <div style="font-size:18px;font-weight:800;color:var(--red)">Rp <?= number_format($combo['price'], 0, ',', '.') ?></div>
                </div>
                <a href="<?= APP_URL ?>/customer/products/?combo=<?= $combo['id'] ?>" class="btn btn-primary btn-round btn-sm" style="padding:8px 20px">Pesan Sekarang</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php customerFooter(); ?>
