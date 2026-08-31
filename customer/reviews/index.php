<?php
require_once __DIR__ . '/../../includes/customer_layout.php';
customerHeader();

$reviews = [
    ['name' => 'Andi Pratama', 'kelas' => 'XII RPL 1', 'rating' => 5, 'text' => 'Nasi gorengnya enak banget! Bumbu meresap dan porsi besar. Pasti order lagi next time 🔥', 'date' => '2 hari lalu', 'product' => 'Nasi Goreng Spesial'],
    ['name' => 'Sari Dewi', 'kelas' => 'XI TKJ 2', 'rating' => 5, 'text' => 'Ayam bakar madunya juara! Manisnya pas, dagingnya empuk. Sangat recommended 👍', 'date' => '3 hari lalu', 'product' => 'Ayam Bakar Madu'],
    ['name' => 'Budi Santoso', 'kelas' => 'XII RPL 2', 'rating' => 4, 'text' => 'Es teh manisnya segar banget, cocok buat cuaca panas. Harga juga murah meriah 😍', 'date' => '5 hari lalu', 'product' => 'Es Teh Manis'],
    ['name' => 'Rina Melati', 'kelas' => 'XI RPL 1', 'rating' => 5, 'text' => 'Sate ayamnya gurih, bumbu kacangnya pas. 10 tusuk puas banget makan nya 🍢', 'date' => '1 minggu lalu', 'product' => 'Sate Ayam'],
    ['name' => 'Dimas Putra', 'kelas' => 'XII TKJ 1', 'rating' => 5, 'text' => 'Rendang dagingnya otentik! Rasanya seperti masakan rumah, santan dan rempahnya kerasa banget 🥘', 'date' => '1 minggu lalu', 'product' => 'Rendang Daging'],
    ['name' => 'Maya Putri', 'kelas' => 'XI RPL 2', 'rating' => 4, 'text' => 'Jus alpukatnya creamy dan segar. Susu kental manisnya balance, ga terlalu manis 🥑', 'date' => '2 minggu lalu', 'product' => 'Jus Alpukat'],
    ['name' => 'Fajar Ramadhan', 'kelas' => 'XII RPL 1', 'rating' => 5, 'text' => 'Es jeruknya fresh banget! Jeruknya berasa asli, ga kayak yang pakai sirup. Mantap! 🍊', 'date' => '2 minggu lalu', 'product' => 'Es Jeruk'],
    ['name' => 'Aulia Rahma', 'kelas' => 'XI TKJ 1', 'rating' => 5, 'text' => 'Pengiriman cepat, makanan masih hangat. Packaging juga rapi. FoodShop emang beda kelas! 🏆', 'date' => '3 minggu lalu', 'product' => 'Nasi Goreng Spesial'],
];
$avgRating = number_format(array_sum(array_column($reviews, 'rating')) / count($reviews), 1);
$totalReviews = count($reviews);
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
                <div style="width:36px;height:36px;background:var(--red-light);color:var(--red);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px"><?= strtoupper(substr($r['name'],0,1)) ?></div>
                <div>
                    <div style="font-size:13px;font-weight:600"><?= sanitize($r['name']) ?></div>
                    <div style="font-size:10px;color:var(--gray)"><?= sanitize($r['kelas']) ?></div>
                </div>
            </div>
            <div style="text-align:right">
                <div style="font-size:12px;color:#f5a623"><?= str_repeat('★', $r['rating']) ?></div>
                <div style="font-size:10px;color:var(--gray)"><?= $r['date'] ?></div>
            </div>
        </div>
        <div style="font-size:11px;color:var(--red);background:var(--red-light);display:inline-block;padding:2px 8px;border-radius:10px;margin-bottom:6px"><?= sanitize($r['product']) ?></div>
        <p style="font-size:12px;line-height:1.6;color:#555"><?= sanitize($r['text']) ?></p>
    </div>
    <?php endforeach; ?>
</div>

<?php customerFooter(); ?>
