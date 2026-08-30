<?php
require_once __DIR__ . '/../../includes/customer_layout.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

$sql = "SELECT p.*,c.name as category_name FROM products p JOIN categories c ON p.category_id=c.id WHERE p.is_active=1";
$params = [];
if ($search) { $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }
if ($category) { $sql .= " AND c.slug=?"; $params[] = $category; }

switch ($sort) {
    case 'price-low': $sql .= " ORDER BY p.price ASC"; break;
    case 'price-high': $sql .= " ORDER BY p.price DESC"; break;
    case 'name-az': $sql .= " ORDER BY p.name ASC"; break;
    default: $sql .= " ORDER BY p.created_at DESC";
}

$products = [];
$categories = [];
if ($pdo) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
    $categories = $pdo->query("SELECT * FROM categories WHERE is_active=1 ORDER BY name")->fetchAll();
}
$wishlist = $_SESSION['wishlist'] ?? [];

$sortLabels = ['newest'=>'Newest','price-low'=>'Price: Low','price-high'=>'Price: High','name-az'=>'Name A-Z'];

// Gambar untuk setiap product berdasarkan slug
$productImages = [
    'nasi-goreng-spesial' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=400&fit=crop',
    'ayam-bakar-madu' => 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?w=400&h=400&fit=crop',
    'sate-ayam' => 'https://images.unsplash.com/photo-1555939594-58d7cb561a1a?w=400&h=400&fit=crop',
    'rendang-daging' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=400&fit=crop',
    'es-teh-manis' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400&h=400&fit=crop',
    'jus-alpukat' => 'https://images.unsplash.com/photo-1553530666-ba2a7512e69d?w=400&h=400&fit=crop',
    'es-jeruk' => 'https://images.unsplash.com/photo-1534431389828-3d1d6c932e3a?w=400&h=400&fit=crop',
];

function getProductImage($slug) {
    global $productImages;
    return $productImages[$slug] ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=400&fit=crop';
}

$food = [
    'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=400&fit=crop',
    'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=400&h=400&fit=crop',
    'https://images.unsplash.com/photo-1551504734-5ee1c4a1479b?w=400&h=400&fit=crop',
    'https://images.unsplash.com/photo-1529692236671-f1f6cf9683ba?w=400&h=400&fit=crop',
    'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&h=400&fit=crop',
    'https://images.unsplash.com/photo-1482049016688-2d3e1b311543?w=400&h=400&fit=crop',
];

customerHeader();
?>

<!-- Filter -->
<div class="section">
    <div class="filter-scroll">
        <a href="<?= APP_URL ?>/customer/products/" class="filter-btn-pill <?= !$category?'active':'' ?>">All</a>
        <?php foreach ($categories as $cat): ?>
        <a href="?category=<?= $cat['slug'] ?>&sort=<?= $sort ?>" class="filter-btn-pill <?= $category===$cat['slug']?'active':'' ?>"><?= sanitize($cat['name']) ?></a>
        <?php endforeach; ?>
    </div>

    <?php if ($search): ?>
    <p style="font-size:12px;color:var(--gray);margin-bottom:12px">Results for "<?= sanitize($search) ?>" — <a href="<?= APP_URL ?>/customer/products/" style="color:var(--red)">Clear</a></p>
    <?php endif; ?>

    <!-- Sort Bar -->
    <div class="sort-bar">
        <span><?= count($products) ?> item<?= count($products)!==1?'s':'' ?></span>
        <span class="sort-current js-filter-toggle"><?= $sortLabels[$sort] ?? 'Newest' ?> ▾</span>
    </div>

    <?php if (empty($products)): ?>
    <div class="empty-state"><div class="icon">🔍</div><h3>No food found</h3><p>Try different keywords</p></div>
    <?php else: ?>
    <div class="product-grid">
        <?php foreach ($products as $i => $p): ?>
         <div class="product-card <?= $p['stock']<=0?'oos':'' ?>">
             <a href="<?= APP_URL ?>/customer/products/detail.php?slug=<?= $p['slug'] ?>" class="product-link">
             <div class="product-img-wrap">
                 <span class="product-badge <?= ['badge-red','badge-orange','badge-green'][$i%3] ?>"><?= ['HOT','NEW','SALE'][$i%3] ?></span>
                 <button class="js-wishlist <?= in_array($p['id'],$wishlist)?'wish-active':'' ?>" data-id="<?= $p['id'] ?>" type="button"></button>
                 <img class="product-img" src="<?= getProductImage($p['slug']) ?>" alt="<?= sanitize($p['name']) ?>" loading="lazy">
            </div>
            </a>
            <div class="product-body">
                <div class="product-name"><?= sanitize($p['name']) ?></div>
                <div class="product-desc"><?= sanitize(substr($p['description'],0,50)) ?></div>
                <div class="product-rating">
                    <span class="star">★</span>
                    <span>4.<?= rand(5,9) ?></span>
                    <span class="count">(<?= rand(1,10) ?>K+)</span>
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
    <?php endif; ?>
</div>

<!-- Sort Filter Panel (Mobile) -->
<div class="filter-panel">
    <div class="filter-panel-handle"></div>
    <h3>Sort By</h3>
    <?php foreach ($sortLabels as $key => $label): ?>
    <a href="?category=<?= $category ?>&sort=<?= $key ?>" class="filter-option <?= $sort===$key?'active':'' ?>">
        <span><?= $label ?></span>
        <span class="check">✓</span>
    </a>
    <?php endforeach; ?>
</div>

<!-- Back to Top -->
<button class="back-to-top">↑</button>

<?php customerFooter(); ?>
