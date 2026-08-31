<?php
require_once __DIR__ . '/../../includes/customer_layout.php';
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) { header('Location: '.APP_URL.'/customer/cart/'); exit; }
$total = 0;
foreach ($cart as $item) { $total += $item['price'] * $item['quantity']; }
$errors = [];
$name = $kelas = $note = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');
    $note = trim($_POST['note'] ?? '');
    if (empty($name)) $errors['name'] = 'Required';
    if (empty($kelas)) $errors['kelas'] = 'Required';
    if (empty($errors)) {
        $items_text = "";
        foreach ($cart as $item) {
            $sub = $item['price'] * $item['quantity'];
            $items_text .= "  🍽 " . $item['name'] . " × " . $item['quantity'] . " = Rp " . number_format($sub, 0, ',', '.') . "\n";
        }
        $wa = "Halo Admin " . APP_NAME . " 👋\n";
        $wa .= "Saya ingin memesan:\n\n";
        $wa .= $items_text . "\n";
        $wa .= "💰 Total: *Rp " . number_format($total, 0, ',', '.') . "*\n\n";
        $wa .= "👤 Nama: " . $name . "\n";
        $wa .= "🏫 Kelas: " . $kelas . "\n";
        if ($note) { $wa .= "📝 Catatan: " . $note . "\n"; }
        $wa .= "\n✅ Mohon dikonfirmasi ya Admin\n";
        $wa .= "Terima kasih 🙏❤️";

        $orderNumber = 'FS-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
        $orderData = [
            'number' => $orderNumber,
            'items' => $cart,
            'total' => $total,
            'name' => $name,
            'kelas' => $kelas,
            'note' => $note,
            'date' => date('Y-m-d H:i:s'),
            'status' => 'pending',
            'wa' => $wa
        ];
        $_SESSION['orders'][] = $orderData;
        $_SESSION['last_order'] = $orderData;
        unset($_SESSION['cart']);

        $waUrl = "https://wa.me/" . WA_NUMBER . "?text=" . urlencode($wa);
        header('Location: ' . $waUrl);
        exit;
    }
}
customerHeader();
?>
<div class="ck">
    <div class="page-title">📦 Checkout</div>
    <?php if (!empty($errors['general'])): ?><div style="background:var(--red-light);padding:10px;border-radius:10px;margin-bottom:14px;color:var(--red);font-size:12px;text-align:center"><?= $errors['general'] ?></div><?php endif; ?>
    <form method="POST">
        <div class="ck-card">
            <h3>Data Pemesan</h3>
            <div class="form-group"><label>Nama Lengkap *</label><input type="text" name="name" value="<?= sanitize($name) ?>" placeholder="Nama kamu" required><?php if (!empty($errors['name'])): ?><div class="form-error"><?= $errors['name'] ?></div><?php endif; ?></div>
            <div class="form-group"><label>Kelas *</label><input type="text" name="kelas" value="<?= sanitize($kelas) ?>" placeholder="e.g. XII RPL 1" required><?php if (!empty($errors['kelas'])): ?><div class="form-error"><?= $errors['kelas'] ?></div><?php endif; ?></div>
            <div class="form-group"><label>Catatan</label><input type="text" name="note" value="<?= sanitize($note) ?>" placeholder="Opsional"></div>
        </div>
        <div class="ck-card">
            <h3>Order Summary</h3>
            <?php foreach ($cart as $item): ?>
            <div class="oi"><span><?= sanitize($item['name']) ?> × <?= $item['quantity'] ?></span><span style="font-weight:600">Rp <?= number_format($item['price']*$item['quantity'],0,',','.') ?></span></div>
            <?php endforeach; ?>
            <div class="cart-row cart-total" style="margin-top:8px"><span style="font-weight:700">Total</span><span class="tv">Rp <?= number_format($total,0,',','.') ?></span></div>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-round" style="padding:13px">💬 Bayar via WhatsApp</button>
    </form>
</div>

<?php customerFooter(); ?>
