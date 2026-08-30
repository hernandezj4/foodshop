<?php
require_once __DIR__ . '/../../config/auth.php';
$product_id = (int)($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';
$cart = $_SESSION['cart'] ?? [];
if ($product_id && isset($cart[$product_id])) {
    if ($action === 'increase') { $cart[$product_id]['quantity']++; }
    elseif ($action === 'decrease') { $cart[$product_id]['quantity']--; if ($cart[$product_id]['quantity'] <= 0) unset($cart[$product_id]); }
    elseif ($action === 'remove') { unset($cart[$product_id]); }
    $_SESSION['cart'] = $cart;
}
header('Location: '.APP_URL.'/customer/cart/');
exit;
