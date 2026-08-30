<?php
require_once __DIR__ . '/../../config/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'msg' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$product_id = (int)($input['product_id'] ?? $_POST['product_id'] ?? 0);
$quantity = max(1, (int)($input['quantity'] ?? $_POST['quantity'] ?? 1));

if ($product_id <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'msg' => 'Invalid product']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id=? AND is_active=1");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'msg' => 'Product not found']);
    exit;
}

if ($product['stock'] < $quantity) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'msg' => 'Insufficient stock']);
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (isset($cart[$product_id])) {
    $newQty = $cart[$product_id]['quantity'] + $quantity;
    if ($newQty > $product['stock']) $newQty = $product['stock'];
    $cart[$product_id]['quantity'] = $newQty;
} else {
    $cart[$product_id] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'slug' => $product['slug'],
        'quantity' => $quantity
    ];
}
$_SESSION['cart'] = $cart;

$cartCount = array_sum(array_column($cart, 'quantity'));

echo json_encode([
    'ok' => true,
    'msg' => $product['name'] . ' added to cart!',
    'cart_count' => $cartCount
]);
