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
<?= file_get_contents(__DIR__ . '/../public/img/icons.svg') ?>
<div class="admin-wrapper">
    <aside class="sidebar">
        <div class="sidebar-brand"><?= APP_NAME ?></div>
        <ul class="sidebar-menu">
            <li><a href="<?= APP_URL ?>/admin/dashboard/" class="<?= $sub==='dashboard'?'active':'' ?>"><?= svgIcon('chart',18) ?> Dashboard</a></li>
            <li><a href="<?= APP_URL ?>/admin/products/" class="<?= $sub==='products'?'active':'' ?>"><?= svgIcon('box',18) ?> Products</a></li>
            <li><a href="<?= APP_URL ?>/admin/categories/" class="<?= $sub==='categories'?'active':'' ?>"><?= svgIcon('tag',18) ?> Categories</a></li>
            <li><a href="<?= APP_URL ?>/admin/orders/" class="<?= $sub==='orders'?'active':'' ?>"><?= svgIcon('order',18) ?> Orders</a></li>
            <li><a href="<?= APP_URL ?>/admin/auth/logout.php" class="sidebar-logout"><?= svgIcon('logout',18) ?> Logout</a></li>
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
