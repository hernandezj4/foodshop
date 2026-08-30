<?php
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$path = rtrim($path, '/');

if ($path === '' || $path === '/') {
    require_once __DIR__ . '/public/index.php';
    exit;
}

$direct = __DIR__ . $path;
if (is_dir($direct)) {
    $idx = rtrim($direct, '/') . '/index.php';
    if (is_file($idx)) { chdir(dirname($idx)); require_once $idx; exit; }
}
if (is_file($direct . '.php')) {
    chdir(dirname($direct));
    require_once $direct . '.php';
    exit;
}
if (is_file($direct)) { return false; }

http_response_code(404);
require_once __DIR__ . '/public/404.php';
