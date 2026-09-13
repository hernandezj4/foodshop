<?php
require_once __DIR__ . '/../config/auth.php';

function svgIcon($name, $size = 20) {
    return '<svg width="'.$size.'" height="'.$size.'" style="display:inline-block;vertical-align:middle"><use href="#icon-'.$name.'"/></svg>';
}

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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
</head>
<body class="desk-mode">
<?= file_get_contents(__DIR__ . '/../public/img/icons.svg') ?>

<!-- Desktop Sidebar -->
<nav class="desk-nav">
    <div class="brand">
        <div class="brand-logo"><?= svgIcon('logo', 32) ?></div>
        <div class="brand-text">
            <?= APP_NAME ?>
        </div>
    </div>
    <div class="desk-nav-links">
        <a href="<?= APP_URL ?>" class="<?= ($currentPage==='index'&&$subPage==='public')?'active':'' ?>">
            <span class="icon"><?= svgIcon('home', 18) ?></span> Home
        </a>
        <a href="<?= APP_URL ?>/customer/products/" class="<?= ($subPage==='products')?'active':'' ?>">
            <span class="icon"><?= svgIcon('menu', 18) ?></span> All Menu
        </a>
    </div>
    <div class="desk-nav-section">Categories</div>
    <div class="desk-nav-links">
        <a href="<?= APP_URL ?>/customer/products/?category=makanan">
            <span class="icon"><?= svgIcon('tag', 18) ?></span> Makanan
        </a>
        <a href="<?= APP_URL ?>/customer/products/?category=minuman">
            <span class="icon"><?= svgIcon('tag', 18) ?></span> Minuman
        </a>
        <a href="<?= APP_URL ?>/customer/products/?category=snack">
            <span class="icon"><?= svgIcon('tag', 18) ?></span> Snack
        </a>
        <a href="<?= APP_URL ?>/customer/products/?category=dessert">
            <span class="icon"><?= svgIcon('tag', 18) ?></span> Dessert
        </a>
    </div>
    <a href="<?= APP_URL ?>/customer/cart/" class="desk-nav-cart">
        <span class="icon"><?= svgIcon('cart', 18) ?></span> Cart
        <span class="badge cart-badge" style="<?= $cartCount>0?'':'display:none' ?>"><?= $cartCount ?></span>
    </a>
    <div class="desk-nav-bottom">
        <a href="<?= APP_URL ?>/customer/orders/">
            <span class="icon"><?= svgIcon('order', 18) ?></span> My Orders
        </a>
        <a href="<?= APP_URL ?>/customer/combos/">
            <span class="icon"><?= svgIcon('combo', 18) ?></span> Combo
        </a>
    </div>
</nav>

<!-- Main Content Wrapper -->
<div class="main-content">

<!-- Mobile Header -->
<header class="header">
    <div class="header-left">
        <div class="greeting">
            <h2><span><?= APP_NAME ?></span></h2>
        </div>
    </div>
    <div class="header-right">
        <a href="<?= APP_URL ?>/customer/cart/" class="icon-btn">
            <?= svgIcon('cart', 20) ?>
            <span class="badge cart-badge" style="<?= $cartCount>0?'':'display:none' ?>"><?= $cartCount ?></span>
        </a>
    </div>
</header>

<!-- Search -->
<div class="search-bar">
    <form method="GET" action="<?= APP_URL ?>/customer/products/">
        <input type="text" name="search" placeholder="Cari makanan favoritmu..." value="<?= sanitize($_GET['search'] ?? '') ?>">
        <button type="submit"><?= svgIcon('search', 18) ?></button>
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
        <span class="icon"><?= svgIcon('home', 22) ?></span>
        <span class="label">Home</span>
    </a>
    <a href="<?= APP_URL ?>/customer/products/" class="nav-item <?= (basename(dirname($_SERVER['PHP_SELF']))==='products')?'active':'' ?>">
        <span class="icon"><?= svgIcon('menu', 22) ?></span>
        <span class="label">Menu</span>
    </a>
    <a href="<?= APP_URL ?>/customer/cart/" class="nav-item center <?= (basename(dirname($_SERVER['PHP_SELF']))==='cart')?'active':'' ?>">
        <span class="icon"><?= svgIcon('cart', 24) ?></span>
        <?php if ($cartCount > 0): ?>
        <span class="nav-badge cart-badge"><?= $cartCount ?></span>
        <?php endif; ?>
        <span class="label">Cart</span>
    </a>
    <a href="<?= APP_URL ?>/customer/orders/" class="nav-item <?= (basename(dirname($_SERVER['PHP_SELF']))==='orders')?'active':'' ?>">
        <span class="icon"><?= svgIcon('order', 22) ?></span>
        <span class="label">Orders</span>
    </a>
    <a href="<?= APP_URL ?>/customer/combos/" class="nav-item <?= (basename(dirname($_SERVER['PHP_SELF']))==='combos')?'active':'' ?>">
        <span class="icon"><?= svgIcon('combo', 22) ?></span>
        <span class="label">Combo</span>
    </a>
</nav>

<!-- Desktop Footer -->
<div class="desk-footer">
    <div class="desk-footer-inner">
        <div class="desk-footer-grid">
            <div class="desk-footer-col">
                <div class="footer-brand">
                    <div class="brand-logo" style="filter:brightness(0) invert(1)"><?= svgIcon('logo', 28) ?></div>
                    <span style="font-weight:800;font-size:1rem;color:#fff"><?= APP_NAME ?></span>
                </div>
                <p>Makanan lezat dan segar langsung dari dapur pilihan. Nikmati sensasi rasa terbaik.</p>
            </div>
            <div class="desk-footer-col">
                <h4>Quick Links</h4>
                <a href="<?= APP_URL ?>">Home</a>
                <a href="<?= APP_URL ?>/customer/products/">Menu</a>
                <a href="<?= APP_URL ?>/customer/orders/">My Orders</a>
                <a href="<?= APP_URL ?>/customer/combos/">Combo</a>
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
                <p>Jl. Raya No. 123, Jakarta</p>
                <p>+62 857 8010 8474</p>
                <p>hello@thamsisfood.com</p>
            </div>
        </div>
        <div class="desk-footer-bottom">
            &copy; <?= date('Y') ?> <?= APP_NAME ?>. All Rights Reserved.
        </div>
    </div>
</div>

<!-- Mobile Footer Spacer -->
<div class="footer-space"></div>

<script>var APP_URL='<?= APP_URL ?>';</script>
<script src="<?= APP_URL ?>/js/app.js"></script>
</body>
</html>
<?php
}
