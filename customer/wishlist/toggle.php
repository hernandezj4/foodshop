<?php
require_once __DIR__ . '/../../config/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$product_id = (int)($input['product_id'] ?? 0);

if ($product_id <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false]);
    exit;
}

$wishlist = $_SESSION['wishlist'] ?? [];

if (in_array($product_id, $wishlist)) {
    $wishlist = array_values(array_filter($wishlist, function($id) use ($product_id) { return $id !== $product_id; }));
    $action = 'removed';
} else {
    $wishlist[] = $product_id;
    $action = 'added';
}

$_SESSION['wishlist'] = $wishlist;

echo json_encode([
    'ok' => true,
    'action' => $action,
    'count' => count($wishlist)
]);
