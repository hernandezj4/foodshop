<?php
$isRailway = !empty($_SERVER['RAILWAY_STATIC_URL']) || !empty($_ENV['RAILWAY_PUBLIC_DOMAIN']);
$appUrl = $isRailway
    ? 'https://' . ($_ENV['RAILWAY_PUBLIC_DOMAIN'] ?? 'cheerful-forgiveness-production.up.railway.app')
    : ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']);
define('APP_NAME', 'FoodShop');
define('APP_URL', rtrim($appUrl, '/'));
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');
define('WA_NUMBER', '6285780108474');
