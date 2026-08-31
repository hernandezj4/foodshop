<?php
require_once __DIR__ . '/../includes/customer_layout.php';

$categories = $pdo ? $pdo->query("SELECT * FROM categories WHERE is_active=1 ORDER BY name")->fetchAll() : [];
$products = $pdo ? $pdo->query("SELECT p.*,c.name as category_name FROM products p JOIN categories c ON p.category_id=c.id WHERE p.is_active=1 ORDER BY p.created_at DESC LIMIT 6")->fetchAll() : [];
$popular = $pdo ? $pdo->query("SELECT p.*,c.name as category_name FROM products p JOIN categories c ON p.category_id=c.id WHERE p.is_active=1 ORDER BY p.stock DESC LIMIT 6")->fetchAll() : [];

// Gambar default jika product belum punya image di DB
$defaultImage = 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=500&h=500&fit=crop';

function getProductImage($p) {
    global $defaultImage;
    if (!empty($p['image'])) {
        if (strpos($p['image'], 'http') === 0) return $p['image'];
        return APP_URL . '/uploads/products/' . $p['image'];
    }
    return $defaultImage;
}

$catImg = [
    'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=100&h=100&fit=crop',
    'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=100&h=100&fit=crop',
    'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=100&h=100&fit=crop',
    'https://images.unsplash.com/photo-1551504734-5ee1c4a1479b?w=100&h=100&fit=crop',
];

customerHeader();
?>

<!-- Hero -->
<div class="hero">
    <div class="hero-slide active">
        <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&h=300&fit=crop" alt="Delicious Food">
    </div>
    <div class="hero-slide">
        <img src="https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=600&h=300&fit=crop" alt="Fresh Meal">
    </div>
    <div class="hero-slide">
        <img src="https://images.unsplash.com/photo-1551504734-5ee1c4a1479b?w=600&h=300&fit=crop" alt="Tasty Food">
    </div>
    <div class="hero-slide">
        <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&h=300&fit=crop" alt="Hot Pizza">
    </div>
    <div class="hero-overlay">
        <div class="hero-tag">Limited Time</div>
        <h1>CRISPY. <span>JUICY.</span><br>IRRESISTIBLE.</h1>
        <p>100% Fresh ingredients. Made with love.</p>
        <a href="<?= APP_URL ?>/customer/products/" class="hero-btn">Order Now →</a>
    </div>
    <div class="hero-dots">
        <span class="active"></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>

<!-- Categories -->
<div class="categories">
    <div class="cat-scroll">
        <a href="<?= APP_URL ?>/customer/products/" class="cat-item active">
            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=80&h=80&fit=crop" alt="All">
            <span>All</span>
        </a>
        <?php foreach ($categories as $i => $cat): ?>
        <a href="<?= APP_URL ?>/customer/products/?category=<?= $cat['slug'] ?>" class="cat-item">
            <img src="<?= $catImg[$i % count($catImg)] ?>" alt="<?= sanitize($cat['name']) ?>">
            <span><?= sanitize($cat['name']) ?></span>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- Popular Combos -->
<div class="section">
    <div class="section-head">
        <h2 class="section-title">Popular Combos</h2>
        <a href="<?= APP_URL ?>/customer/products/" class="section-link">View All →</a>
    </div>
    <div class="product-grid">
         <?php foreach ($products as $i => $p): ?>
         <div class="product-card <?= $p['stock']<=0?'oos':'' ?>">
             <a href="<?= APP_URL ?>/customer/products/detail.php?slug=<?= $p['slug'] ?>" class="product-link">
             <div class="product-img-wrap">
                 <span class="product-badge <?= ['badge-red','badge-orange','badge-green'][$i%3] ?>"><?= ['BESTSELLER','POPULAR','SAVE 15%'][$i%3] ?></span>
                 <button class="js-wishlist" data-id="<?= $p['id'] ?>" type="button"></button>
                 <img class="product-img" src="<?= getProductImage($p) ?>" alt="<?= sanitize($p['name']) ?>" loading="lazy">
            </div>
            </a>
            <div class="product-body">
                <div class="product-name"><?= sanitize($p['name']) ?></div>
                <div class="product-desc"><?= sanitize(substr($p['description'],0,50)) ?></div>
                <div class="product-rating">
                    <span class="star">★</span>
                    <span>4.<?= rand(5,9) ?></span>
                    <span class="count">(<?= rand(1,12) ?>K+)</span>
                </div>
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
</div>

<!-- Promo Banner -->
<div class="promo-banner">
    <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=200&h=200&fit=crop" alt="Promo">
    <div class="promo-text">
        <div class="promo-tag">Free Delivery</div>
        <h3>Gratis Ongkir!</h3>
        <p>Pesan sekarang dan nikmati gratis pengiriman</p>
        <a href="<?= APP_URL ?>/customer/products/" class="promo-btn">Order Now →</a>
    </div>
    <div class="promo-discount">
        <small>FREE</small>
        <span style="font-size:1rem">🚚</span>
        <small>DELIVERY</small>
    </div>
</div>

<!-- Daily Best -->
<div class="section">
    <div class="section-head">
        <h2 class="section-title">Daily Best Sells</h2>
        <a href="<?= APP_URL ?>/customer/products/" class="section-link">View All →</a>
    </div>
    <div class="product-grid">
         <?php foreach ($popular as $i => $p): ?>
         <div class="product-card <?= $p['stock']<=0?'oos':'' ?>">
             <a href="<?= APP_URL ?>/customer/products/detail.php?slug=<?= $p['slug'] ?>" class="product-link">
             <div class="product-img-wrap">
                 <button class="js-wishlist" data-id="<?= $p['id'] ?>" type="button"></button>
                 <img class="product-img" src="<?= getProductImage($p) ?>" alt="<?= sanitize($p['name']) ?>" loading="lazy">
            </div>
            </a>
            <div class="product-body">
                <div class="product-name"><?= sanitize($p['name']) ?></div>
                <div class="product-desc"><?= sanitize(substr($p['description'],0,50)) ?></div>
                <div class="product-rating">
                    <span class="star">★</span>
                    <span>4.<?= rand(5,9) ?></span>
                    <span class="count">(<?= rand(1,8) ?>K+)</span>
                </div>
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
</div>

<?php customerFooter(); ?>
