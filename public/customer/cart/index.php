<?php
require_once __DIR__ . '/../../includes/customer_layout.php';
$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) { $total += $item['price'] * $item['quantity']; }
$food = [
    'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=120&h=120&fit=crop',
    'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=120&h=120&fit=crop',
    'https://images.unsplash.com/photo-1551504734-5ee1c4a1479b?w=120&h=120&fit=crop',
];
customerHeader();
?>
<div class="cart-page">
    <div class="page-title">🛒 Your Cart</div>
    <?php if (empty($cart)): ?>
    <div class="empty-state"><div class="icon">🛒</div><h3>Cart is empty</h3><p>Add some delicious food!</p><a href="<?= APP_URL ?>/customer/products/" class="btn btn-primary btn-round btn-sm">Browse Menu</a></div>
    <?php else: ?>
    <?php $i=0; foreach ($cart as $id => $item): ?>
    <div class="cart-item">
        <img class="cart-item-img" src="<?= $food[$i%count($food)] ?>" alt="" loading="lazy">
        <div class="cart-item-info">
            <div class="cart-item-name"><?= sanitize($item['name']) ?></div>
            <div class="cart-item-price">Rp <?= number_format($item['price'],0,',','.') ?></div>
            <div class="qty-control">
                <a href="<?= APP_URL ?>/customer/cart/update.php?id=<?= $id ?>&action=decrease" class="qty-btn">−</a>
                <span class="qty-value"><?= $item['quantity'] ?></span>
                <a href="<?= APP_URL ?>/customer/cart/update.php?id=<?= $id ?>&action=increase" class="qty-btn">+</a>
            </div>
        </div>
        <div style="text-align:right">
            <div class="cart-item-price" style="margin-bottom:4px">Rp <?= number_format($item['price']*$item['quantity'],0,',','.') ?></div>
            <a href="<?= APP_URL ?>/customer/cart/update.php?id=<?= $id ?>&action=remove" class="cart-remove js-remove-cart" data-name="<?= sanitize($item['name']) ?>">✕</a>
        </div>
    </div>
    <?php $i++; endforeach; ?>

    <div class="cart-summary" style="margin-top:14px">
        <div class="cart-row"><span>Subtotal</span><span>Rp <?= number_format($total,0,',','.') ?></span></div>
        <div class="cart-row"><span>Delivery</span><span style="color:#38a169;font-weight:600">Free</span></div>
        <div class="cart-row cart-total"><span style="font-weight:700">Total</span><span class="tv">Rp <?= number_format($total,0,',','.') ?></span></div>
        <a href="<?= APP_URL ?>/customer/checkout/" class="btn btn-primary btn-block btn-round" style="margin-top:14px;padding:13px">Checkout →</a>
    </div>
    <?php endif; ?>
</div>

<?php customerFooter(); ?>
