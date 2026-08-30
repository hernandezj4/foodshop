<?php
$_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/..';

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($uri, PHP_URL_PATH);
$path = rtrim($path, '/');

$map = [
    '' => '/public/index.php',
    '/' => '/public/index.php',
    '/customer' => '/customer/products/index.php',
    '/customer/' => '/customer/products/index.php',
    '/customer/products' => '/customer/products/index.php',
    '/customer/products/' => '/customer/products/index.php',
    '/customer/cart' => '/customer/cart/index.php',
    '/customer/cart/' => '/customer/cart/index.php',
    '/customer/checkout' => '/customer/checkout/index.php',
    '/customer/checkout/' => '/customer/checkout/index.php',
    '/customer/wishlist' => '/customer/wishlist/toggle.php',
    '/customer/wishlist/' => '/customer/wishlist/toggle.php',
    '/admin' => '/admin/dashboard/index.php',
    '/admin/' => '/admin/dashboard/index.php',
    '/admin/dashboard' => '/admin/dashboard/index.php',
    '/admin/dashboard/' => '/admin/dashboard/index.php',
    '/admin/products' => '/admin/products/index.php',
    '/admin/products/' => '/admin/products/index.php',
    '/admin/categories' => '/admin/categories/index.php',
    '/admin/categories/' => '/admin/categories/index.php',
    '/admin/orders' => '/admin/orders/index.php',
    '/admin/orders/' => '/admin/orders/index.php',
];

$root = dirname(__DIR__);

if (isset($map[$path])) {
    $file = $root . $map[$path];
    if (is_file($file)) {
        require_once $file;
        exit;
    }
}

$direct = $root . $path;
if (is_dir($direct)) {
    $direct = rtrim($direct, '/') . '/index.php';
}
if (is_file($direct) && pathinfo($direct, PATHINFO_EXTENSION) === 'php') {
    require_once $direct;
    exit;
}

if (is_file($root . $path . '.php')) {
    require_once $root . $path . '.php';
    exit;
}

http_response_code(404);
require_once $root . '/public/404.php';
