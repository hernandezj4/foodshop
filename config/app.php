<?php
$isVercel = !empty($_SERVER['VERCEL']) || !empty($_SERVER['VERCEL_ENV']);
define('APP_NAME', 'FoodShop');
define('APP_URL', $isVercel ? 'https://foodshop-ochre.vercel.app' : 'http://localhost/projecet-kdi/public');
define('UPLOAD_PATH', $isVercel ? '/tmp/uploads/' : __DIR__ . '/../public/uploads/');
define('WA_NUMBER', '6285780108474');
