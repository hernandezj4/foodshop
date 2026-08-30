<?php
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$path = rtrim($path, '/');

$root = __DIR__;

$public = $root . '/public';
$direct = $public . $path;

if ($path === '' || $path === '/') {
    require_once $public . '/index.php';
    exit;
}

if (is_file($direct)) {
    if (pathinfo($direct, PATHINFO_EXTENSION) === 'php') {
        chdir(dirname($direct));
        require_once $direct;
        exit;
    }
    return false;
}

if (is_dir($direct)) {
    $idx = rtrim($direct, '/') . '/index.php';
    if (is_file($idx)) { chdir(dirname($idx)); require_once $idx; exit; }
}

$rootDirect = $root . $path;
if (is_dir($rootDirect)) {
    $idx = rtrim($rootDirect, '/') . '/index.php';
    if (is_file($idx)) { chdir(dirname($idx)); require_once $idx; exit; }
}
if (is_file($rootDirect . '.php')) {
    chdir(dirname($rootDirect));
    require_once $rootDirect . '.php';
    exit;
}

http_response_code(404);
require_once $public . '/404.php';
