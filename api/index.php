<?php
$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__);

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($uri, PHP_URL_PATH);
$path = rtrim($path, '/');

$root = dirname(__DIR__);

$direct = $root . $path;
if (is_dir($direct)) {
    $direct = rtrim($direct, '/') . '/index.php';
}
if (is_file($direct) && pathinfo($direct, PATHINFO_EXTENSION) === 'php') {
    chdir(dirname($direct));
    require_once $direct;
    exit;
}

if (is_file($root . $path . '.php')) {
    chdir($root . $path);
    require_once $root . $path . '.php';
    exit;
}

http_response_code(404);
require_once $root . '/public/404.php';
