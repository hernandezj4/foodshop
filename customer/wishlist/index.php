<?php
require_once __DIR__ . '/../../includes/customer_layout.php';

$wishlist = $_SESSION['wishlist'] ?? [];
$products = [];
if (!empty($wishlist)) {
    $placeholders = implode(',', array_fill(0, count($wishlist), '?'));
    $stmt = $pdo->prepare("SELECT p.*,c.name as category_name FROM products p JOIN categories c ON p.category_id=c.id WHERE p.id IN ($placeholders) AND p.is_active=1");
    $stmt->execute($wishlist);
    $products = $stmt->fetchAll();
}

$defaultImage = 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=500&h=500&fit=crop';
function getWishImage($p) {
    global $defaultImage;
    if (!empty($p['image'])) {
        if (strpos($p['image'], 'http') === 0) return $p['image'];
        return APP_URL . '/uploads/products/' . $p['image'];
    }
    return $defaultImage;
}

customerHeader();
?>

<div class="section">
    <h2 class="page-title" style="padding:0 0 14px">❤️ My Wishlist</h2>

    <?php if (empty($products)): ?>
    <div class="empty-state">
        <div class="icon">❤️</div>
        <h3>Belum ada wishlist</h3>
        <p>Tandai produk favoritmu dengan tombol hati</p>
        <a href="<?= APP_URL ?>/customer/products/" class="btn btn-primary btn-round">Browse Menu</a>
    </div>
    <?php else: ?>
    <div class="product-grid">
        <?php foreach ($products as $i => $p): ?>
        <div class="product-card <?= $p['stock']<=0?'oos':'' ?>">
            <a href="<?= APP_URL ?>/customer/products/detail.php?slug=<?= $p['slug'] ?>" class="product-link">
            <div class="product-img-wrap">
                <button class="js-wishlist wish-active" data-id="<?= $p['id'] ?>" type="button"></button>
                <img class="product-img" src="<?= getWishImage($p) ?>" alt="<?= sanitize($p['name']) ?>" loading="lazy">
            </div>
            </a>
            <div class="product-body">
                <div class="product-name"><?= sanitize($p['name']) ?></div>
                <div class="product-desc"><?= sanitize(substr($p['description'],0,50)) ?></div>
                <div class="product-bottom">
                    <span class="product-price">Rp <?= number_format($p['price'],0,',','.') ?></span>
                    <?php if ($p['stock']>0): ?>
                    <form method="POST" action="<?= APP_URL ?>/customer/cart/add.php" style="margin:0">
                        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="product-add js-add-cart" title="Add to Cart">+</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php customerFooter(); ?>
