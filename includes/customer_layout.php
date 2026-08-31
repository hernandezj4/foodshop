<?php
require_once __DIR__ . '/../config/auth.php';

function customerHeader() {
    $cart = $_SESSION['cart'] ?? [];
    $cartCount = array_sum(array_column($cart, 'quantity'));
    $currentPage = basename($_SERVER['PHP_SELF'], '.php');
    $subPage = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
</head>
<body class="desk-mode">

<!-- Desktop Sidebar -->
<nav class="desk-nav">
    <div class="brand">
        <span class="brand-icon">🍔</span>
        <?= APP_NAME ?>
        <small>Food XI RPL 2</small>
    </div>
    <div class="desk-nav-links">
        <a href="<?= APP_URL ?>" class="<?= ($currentPage==='index'&&$subPage==='public')?'active':'' ?>">
            <span class="icon">🏠</span> Home
        </a>
        <a href="<?= APP_URL ?>/customer/products/" class="<?= ($subPage==='products')?'active':'' ?>">
            <span class="icon">📋</span> All Menu
        </a>
    </div>
    <div class="desk-nav-section">Categories</div>
    <div class="desk-nav-links">
        <a href="<?= APP_URL ?>/customer/products/?category=makanan">
            <span class="icon">🍛</span> Makanan
        </a>
        <a href="<?= APP_URL ?>/customer/products/?category=minuman">
            <span class="icon">🧃</span> Minuman
        </a>
        <a href="<?= APP_URL ?>/customer/products/?category=snack">
            <span class="icon">🍿</span> Snack
        </a>
        <a href="<?= APP_URL ?>/customer/products/?category=dessert">
            <span class="icon">🍰</span> Dessert
        </a>
    </div>
    <a href="<?= APP_URL ?>/customer/cart/" class="desk-nav-cart">
        🛒 Cart
        <span class="badge cart-badge" style="<?= $cartCount>0?'':'display:none' ?>"><?= $cartCount ?></span>
    </a>
    <div class="desk-nav-bottom">
        <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo%20FoodShop%2C%20saya%20mau%20tanya-tanya" target="_blank" style="display:flex;align-items:center;gap:10px;padding:10px 16px;color:#25D366;font-size:13px;border-left:3px solid transparent;text-decoration:none">
            <span class="icon">💬</span> Chat WhatsApp
        </a>
    </div>
</nav>

<!-- Main Content Wrapper -->
<div class="main-content">

<!-- Mobile Header (hidden on desktop via CSS) -->
<header class="header">
    <div class="header-left">
        <div class="greeting">
            <small>Hello, Food Lover! 👋</small>
            <h2><span><?= APP_NAME ?></span></h2>
            <p>Food XI RPL 2</p>
        </div>
    </div>
    <div class="header-right">
        <a href="<?= APP_URL ?>/customer/cart/" class="icon-btn">
            🛒
            <span class="badge cart-badge" style="<?= $cartCount>0?'':'display:none' ?>"><?= $cartCount ?></span>
        </a>
    </div>
</header>

<!-- Search -->
<div class="search-bar">
    <form method="GET" action="<?= APP_URL ?>/customer/products/">
        <input type="text" name="search" placeholder="Search for your favorite food..." value="<?= sanitize($_GET['search'] ?? '') ?>">
        <button type="submit">🔍</button>
    </form>
</div>
<?php
}

function customerFooter() {
    $cart = $_SESSION['cart'] ?? [];
    $cartCount = array_sum(array_column($cart, 'quantity'));
?>
</div><!-- /.main-content -->

<!-- Mobile Bottom Nav -->
<nav class="bottom-nav">
    <a href="<?= APP_URL ?>" class="nav-item <?= (basename($_SERVER['PHP_SELF'])==='index'&&basename(dirname($_SERVER['PHP_SELF']))==='public')?'active':'' ?>">
        <span class="icon">🏠</span>
        <span class="label">Home</span>
    </a>
    <a href="<?= APP_URL ?>/customer/products/" class="nav-item <?= (basename(dirname($_SERVER['PHP_SELF']))==='products')?'active':'' ?>">
        <span class="icon">📋</span>
        <span class="label">Menu</span>
    </a>
    <a href="<?= APP_URL ?>/customer/cart/" class="nav-item center <?= (basename(dirname($_SERVER['PHP_SELF']))==='cart')?'active':'' ?>">
        <span class="icon">🛒</span>
        <?php if ($cartCount > 0): ?>
        <span class="nav-badge cart-badge"><?= $cartCount ?></span>
        <?php endif; ?>
        <span class="label">Cart</span>
    </a>
    <a href="<?= APP_URL ?>/customer/wishlist/" class="nav-item <?= (basename(dirname($_SERVER['PHP_SELF']))==='wishlist')?'active':'' ?>">
        <span class="icon">❤️</span>
        <span class="label">Wishlist</span>
    </a>
    <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo%20FoodShop%2C%20saya%20mau%20pesan%20makanan" target="_blank" class="nav-item">
        <span class="icon">💬</span>
        <span class="label">WhatsApp</span>
    </a>
</nav>

<!-- Desktop Footer -->
<div class="desk-footer">
    <div class="desk-footer-inner">
        <div class="desk-footer-grid">
            <div class="desk-footer-col">
                <h4><?= APP_NAME ?></h4>
                <p>Delicious food delivered fresh to your doorstep. Taste the best meals from local chefs.</p>
            </div>
            <div class="desk-footer-col">
                <h4>Quick Links</h4>
                <a href="<?= APP_URL ?>">Home</a>
                <a href="<?= APP_URL ?>/customer/products/">Menu</a>
                <a href="<?= APP_URL ?>/customer/wishlist/">Wishlist</a>
                <a href="https://wa.me/<?= WA_NUMBER ?>" target="_blank">Chat WhatsApp</a>
            </div>
            <div class="desk-footer-col">
                <h4>Categories</h4>
                <a href="<?= APP_URL ?>/customer/products/?category=makanan">Makanan</a>
                <a href="<?= APP_URL ?>/customer/products/?category=minuman">Minuman</a>
                <a href="<?= APP_URL ?>/customer/products/?category=snack">Snack</a>
                <a href="<?= APP_URL ?>/customer/products/?category=dessert">Dessert</a>
            </div>
            <div class="desk-footer-col">
                <h4>Contact</h4>
                <p>📍 Jl. Raya No. 123, Jakarta</p>
                <p>📞 +62 812 3456 7890</p>
                <p>📧 hello@<?= strtolower(APP_NAME) ?>.com</p>
            </div>
        </div>
        <div class="desk-footer-bottom">
            &copy; <?= date('Y') ?> <?= APP_NAME ?>. All Rights Reserved.
        </div>
    </div>
</div>

<!-- Mobile Footer Spacer -->
<div class="footer-space"></div>

<!-- Floating WhatsApp Button -->
<a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo%20FoodShop%2C%20saya%20mau%20pesan%20makanan" target="_blank" style="position:fixed;bottom:80px;right:16px;width:54px;height:54px;background:#25D366;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:26px;color:#fff;box-shadow:0 4px 12px rgba(37,211,102,.4);z-index:99;text-decoration:none;transition:.2s" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">💬</a>

<script>var APP_URL='<?= APP_URL ?>';</script>
<script src="<?= APP_URL ?>/js/app.js"></script>
</body>
</html>
<?php
}