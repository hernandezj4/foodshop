<?php
require_once __DIR__ . '/../../includes/customer_layout.php';
require_once __DIR__ . '/../../config/database.php';

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT p.*,c.name as category_name FROM products p JOIN categories c ON p.category_id=c.id WHERE p.slug=? AND p.is_active=1");
$stmt->execute([$slug]);
$product = $stmt->fetch();
if (!$product) { header('Location: '.APP_URL.'/customer/products/'); exit; }

$related = $pdo->prepare("SELECT p.*,c.name as category_name FROM products p JOIN categories c ON p.category_id=c.id WHERE p.category_id=? AND p.id!=? AND p.is_active=1 LIMIT 4");
$related->execute([$product['category_id'],$product['id']]);
$relatedProducts = $related->fetchAll();

$wishlist = $_SESSION['wishlist'] ?? [];
$isWished = in_array($product['id'], $wishlist);

$food = [
    'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=400&fit=crop',
    'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=400&h=400&fit=crop',
    'https://images.unsplash.com/photo-1551504734-5ee1c4a1479b?w=400&h=400&fit=crop',
];

customerHeader();
?>

<div class="pd">
    <div style="position:relative">
        <img class="pd-img" src="<?= $food[0] ?>" alt="<?= sanitize($product['name']) ?>" loading="lazy">
        <button class="js-wishlist <?= $isWished?'wish-active':'' ?>" data-id="<?= $product['id'] ?>" type="button" style="position:absolute;top:12px;right:12px;width:36px;height:36px;font-size:18px"></button>
    </div>
    <div class="pd-info">
        <span class="cat"><?= $product['category_name'] ?></span>
        <h1><?= sanitize($product['name']) ?></h1>
        <div class="product-rating" style="margin:6px 0">
            <span class="star">★</span>
            <span>4.<?= rand(5,9) ?></span>
            <span class="count">(<?= rand(1,12) ?>K+ reviews)</span>
        </div>
        <p class="price">Rp <?= number_format($product['price'],0,',','.') ?></p>
        <p class="desc"><?= sanitize($product['description']) ?></p>
        <?php if ($product['stock']>0): ?>
        <p style="font-size:12px;color:var(--gray);margin-bottom:14px">Stock: <strong style="color:#38a169"><?= $product['stock']?> available</strong>
            <?php if ($product['stock']<=5): ?><span style="color:#f57c00;font-weight:600"> — Low stock!</span><?php endif; ?>
        </p>
        <?php else: ?>
        <p style="font-size:12px;color:var(--red);margin-bottom:14px;font-weight:600">Out of Stock</p>
        <?php endif; ?>
        <?php if ($product['stock']>0): ?>
        <form method="POST" action="<?= APP_URL ?>/customer/cart/add.php" class="detail-cart-form">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <div class="qty-control" style="margin-bottom:14px">
                <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                <span class="qty-value" id="qty">1</span>
                <input type="hidden" name="quantity" id="qty-input" value="1">
                <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-round js-add-cart" style="padding:13px">🛒 Add to Cart</button>
        </form>
        <?php else: ?>
        <button class="btn btn-secondary btn-block btn-round" disabled style="padding:13px;opacity:.5">Out of Stock</button>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($relatedProducts)): ?>
<div class="section">
    <div class="section-head"><h2 class="section-title">You Might Like</h2></div>
    <div class="product-grid">
        <?php foreach ($relatedProducts as $i => $p): ?>
        <div class="product-card <?= $p['stock']<=0?'oos':'' ?>">
            <a href="<?= APP_URL ?>/customer/products/detail.php?slug=<?= $p['slug'] ?>" class="product-link">
            <div class="product-img-wrap">
                <button class="js-wishlist <?= in_array($p['id'],$wishlist)?'wish-active':'' ?>" data-id="<?= $p['id'] ?>" type="button"></button>
                <img class="product-img" src="<?= $food[($i+1)%count($food)] ?>" alt="<?= sanitize($p['name']) ?>" loading="lazy">
            </div>
            </a>
            <div class="product-body">
                <div class="product-name"><?= sanitize($p['name']) ?></div>
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
<?php endif; ?>

<script>
function changeQty(d){var q=document.getElementById('qty'),i=document.getElementById('qty-input'),v=parseInt(q.textContent)+d;if(v<1)v=1;if(v><?= $product['stock'] ?>)v=<?= $product['stock'] ?>;q.textContent=v;i.value=v;}
</script>

<?php customerFooter(); ?>
