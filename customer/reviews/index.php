<?php
require_once __DIR__ . '/../../includes/customer_layout.php';
customerHeader();

$reviews = $pdo->query("SELECT r.*, p.name as product_name FROM reviews r JOIN products p ON r.product_id = p.id WHERE r.is_active = 1 ORDER BY r.created_at DESC")->fetchAll();
$totalReviews = count($reviews);
$avgRating = $totalReviews > 0 ? number_format(array_sum(array_column($reviews, 'rating')) / $totalReviews, 1) : '0.0';
?>

<div class="section">
    <h2 class="page-title" style="padding:0 0 4px">⭐ Customer Reviews</h2>
    <p style="font-size:12px;color:var(--gray);margin-bottom:16px"><?= $totalReviews ?> reviews · Rata-rata <?= $avgRating ?>/5</p>

    <!-- Rating Summary -->
    <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;margin-bottom:16px;text-align:center">
        <div style="font-size:2.5rem;font-weight:800;color:var(--dark)"><?= $avgRating ?></div>
        <div style="font-size:1.2rem;margin:4px 0">⭐⭐⭐⭐⭐</div>
        <div style="font-size:12px;color:var(--gray)">Berdasarkan <?= $totalReviews ?> reviews</div>
    </div>

    <!-- Review List -->
    <?php foreach ($reviews as $r): ?>
    <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow);padding:16px;margin-bottom:10px">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px">
            <div style="display:flex;gap:10px;align-items:center">
                <div style="width:36px;height:36px;background:var(--red-light);color:var(--red);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px"><?= strtoupper(substr($r['customer_name'],0,1)) ?></div>
                <div>
                    <div style="font-size:13px;font-weight:600"><?= sanitize($r['customer_name']) ?></div>
                    <div style="font-size:10px;color:var(--gray)"><?= sanitize($r['kelas']) ?></div>
                </div>
            </div>
            <div style="text-align:right">
                <div style="font-size:12px;color:#f5a623"><?= str_repeat('★', $r['rating']) ?></div>
                <div style="font-size:10px;color:var(--gray)"><?= date('d M Y', strtotime($r['created_at'])) ?></div>
            </div>
        </div>
        <div style="font-size:11px;color:var(--red);background:var(--red-light);display:inline-block;padding:2px 8px;border-radius:10px;margin-bottom:6px"><?= sanitize($r['product_name']) ?></div>
        <p style="font-size:12px;line-height:1.6;color:#555"><?= sanitize($r['review_text']) ?></p>
    </div>
    <?php endforeach; ?>

    <?php if ($totalReviews === 0): ?>
    <div style="text-align:center;padding:40px;color:var(--gray)">
        <div style="font-size:2rem;margin-bottom:8px">⭐</div>
        <p>Belum ada reviews</p>
    </div>
    <?php endif; ?>
</div>

<?php customerFooter(); ?>
