<?php
header('Content-Type: text/plain');

echo "=== RAILWAY ENVIRONMENT DIAGNOSTICS ===\n\n";

// Check if running on Railway
$isLinux = php_uname('s') === 'Linux';
$isRailway = $isLinux && getenv('RAILWAY_ENVIRONMENT_NAME');

echo "Platform: " . php_uname('s') . "\n";
echo "Is Railway: " . ($isRailway ? 'YES' : 'NO') . "\n\n";

echo "=== ALL ENV VARS STARTING WITH MYSQL ===\n";
$allEnv = $_ENV + $_SERVER;
foreach ($allEnv as $key => $value) {
    if (strpos($key, 'MYSQL') === 0) {
        echo "$key: " . (is_string($value) && strlen($value) > 0 ? substr($value, 0, 50) : 'EMPTY/NULL') . "\n";
    }
}

echo "\n=== RAILWAY-SPECIFIC ENV VARS ===\n";
foreach ($allEnv as $key => $value) {
    if (strpos($key, 'RAILWAY') === 0) {
        echo "$key: " . (is_string($value) && strlen($value) > 0 ? substr($value, 0, 50) : 'EMPTY/NULL') . "\n";
    }
}

echo "\n=== CONNECTION STRING ===\n";
$host = getenv('MYSQLHOST') ?: 'NOT SET';
$port = getenv('MYSQLPORT') ?: 'NOT SET';
$dbname = getenv('MYSQLDATABASE') ?: 'NOT SET';
$user = getenv('MYSQLUSER') ?: 'NOT SET';
$pass = getenv('MYSQLPASSWORD') ? 'SET' : 'NOT SET';

echo "Host: $host\n";
echo "Port: $port\n";
echo "Database: $dbname\n";
echo "User: $user\n";
echo "Password: $pass\n";
?>
