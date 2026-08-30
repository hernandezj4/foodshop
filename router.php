<?php
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$path = rtrim($path, '/');

$root = __DIR__;
$public = $root . '/public';

if ($path === '' || $path === '/') {
    require_once $public . '/index.php';
    exit;
}

$rootFile = $root . $path;
if (is_file($rootFile . '.php')) {
    chdir(dirname($rootFile));
    require_once $rootFile . '.php';
    exit;
}
if (is_dir($rootFile)) {
    $idx = rtrim($rootFile, '/') . '/index.php';
    if (is_file($idx)) { chdir(dirname($idx)); require_once $idx; exit; }
}

$publicFile = $public . $path;
if (is_file($publicFile . '.php')) {
    chdir(dirname($publicFile));
    require_once $publicFile . '.php';
    exit;
}
if (is_file($publicFile) && pathinfo($publicFile, PATHINFO_EXTENSION) === 'php') {
    chdir(dirname($publicFile));
    require_once $publicFile;
    exit;
}
if (is_dir($publicFile)) {
    $idx = rtrim($publicFile, '/') . '/index.php';
    if (is_file($idx)) { chdir(dirname($idx)); require_once $idx; exit; }
}

if (is_file($publicFile)) {
    $ext = pathinfo($publicFile, PATHINFO_EXTENSION);
    $mimeTypes = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon',
        'webp' => 'image/webp',
        'woff' => 'font/woff',
        'woff2'=> 'font/woff2',
        'ttf'  => 'font/ttf',
        'json' => 'application/json',
    ];
    header('Content-Type: ' . ($mimeTypes[$ext] ?? mime_content_type($publicFile) ?: 'application/octet-stream'));
    header('Content-Length: ' . filesize($publicFile));
    readfile($publicFile);
    exit;
}

http_response_code(404);
require_once $public . '/404.php';
