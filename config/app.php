<?php
error_reporting(E_ALL);
if (php_sapi_name() !== 'cli') {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../logs/error.log');
}

$isRailway = getenv('RAILWAY_PUBLIC_DOMAIN') !== false;
$appUrl = $isRailway
    ? 'https://' . getenv('RAILWAY_PUBLIC_DOMAIN')
    : ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
define('APP_NAME', 'Thamsis Food');
define('APP_URL', rtrim($appUrl, '/'));
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');
define('WA_NUMBER', '6285780108474');

function svgIcon($name, $size = 20) {
    return '<svg width="'.$size.'" height="'.$size.'" style="display:inline-block;vertical-align:middle"><use href="#icon-'.$name.'"/></svg>';
}
