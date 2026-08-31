<?php
require_once __DIR__ . '/../../config/auth.php';
$product_id = (int)($_POST['product_id'] ?? 0);
$quantity = max(1, (int)($_POST['quantity'] ?? 1));
if ($product_id > 0) {
    require_once __DIR__ . '/../../config/database.php';
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=? AND is_active=1");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    if ($product) {
        if ($product['stock'] <= 0) {
            header('Location: '.APP_URL.'/customer/products/detail.php?slug='.$product['slug'].'&error=out_of_stock');
            exit;
        }
        $cart = $_SESSION['cart'] ?? [];
        if (isset($cart[$product_id])) {
            $newQty = $cart[$product_id]['quantity'] + $quantity;
            if ($newQty > $product['stock']) $newQty = $product['stock'];
            $cart[$product_id]['quantity'] = $newQty;
        } else {
            if ($quantity > $product['stock']) $quantity = $product['stock'];
            $cart[$product_id] = ['id'=>$product['id'],'name'=>$product['name'],'price'=>$product['price'],'slug'=>$product['slug'],'image'=>$product['image']??'','quantity'=>$quantity];
        }
        $_SESSION['cart'] = $cart;
    }
}
header('Location: '.APP_URL.'/customer/cart/');
exit;
