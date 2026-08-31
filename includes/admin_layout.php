<?php
require_once __DIR__ . '/../config/auth.php';
requireLogin();
function adminHeader() {
    $sub = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
</head>
<body>
<div class="admin-wrapper">
    <aside class="sidebar">
        <div class="sidebar-brand"><?= APP_NAME ?></div>
        <ul class="sidebar-menu">
            <li><a href="<?= APP_URL ?>/admin/dashboard/" class="<?= $sub==='dashboard'?'active':'' ?>">📊 Dashboard</a></li>
            <li><a href="<?= APP_URL ?>/admin/products/" class="<?= $sub==='products'?'active':'' ?>">📦 Products</a></li>
            <li><a href="<?= APP_URL ?>/admin/categories/" class="<?= $sub==='categories'?'active':'' ?>">🏷️ Categories</a></li>
            <li><a href="<?= APP_URL ?>/admin/orders/" class="<?= $sub==='orders'?'active':'' ?>">📋 Orders</a></li>
            <li><a href="<?= APP_URL ?>/admin/reviews/" class="<?= $sub==='reviews'?'active':'' ?>">⭐ Reviews</a></li>
            <li><a href="<?= APP_URL ?>/admin/auth/logout.php" class="sidebar-logout">🚪 Logout</a></li>
        </ul>
    </aside>
    <main class="admin-content">
<?php
}
function adminFooter() { ?>
    </main>
</div>
<script>var APP_URL='<?= APP_URL ?>';</script>
<script src="<?= APP_URL ?>/js/app.js"></script>
</body>
</html>
<?php } ?>
