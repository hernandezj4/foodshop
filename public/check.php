<?php
header('Content-Type: text/plain');

echo "=== RAILWAY ENV VARS CHECK ===\n\n";

$host = $_ENV['MYSQLHOST'] ?? getenv('MYSQLHOST') ?? 'NOT SET';
$port = $_ENV['MYSQLPORT'] ?? getenv('MYSQLPORT') ?? 'NOT SET';
$dbname = $_ENV['MYSQLDATABASE'] ?? getenv('MYSQLDATABASE') ?? 'NOT SET';
$username = $_ENV['MYSQLUSER'] ?? getenv('MYSQLUSER') ?? 'NOT SET';
$password = $_ENV['MYSQLPASSWORD'] ?? getenv('MYSQLPASSWORD') ?? 'NOT SET';

echo "MYSQLHOST: $host\n";
echo "MYSQLPORT: $port\n";
echo "MYSQLDATABASE: $dbname\n";
echo "MYSQLUSER: $username\n";
echo "MYSQLPASSWORD: " . (($password !== 'NOT SET') ? '***SET***' : 'NOT SET') . "\n\n";

if ($host === 'NOT SET') {
    echo "✗ ENV VARS NOT SET - Railway env vars tidak terdeteksi!\n";
    exit;
}

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    
    $products = $pdo->query("SELECT COUNT(*) as cnt FROM products")->fetch();
    $categories = $pdo->query("SELECT COUNT(*) as cnt FROM categories")->fetch();
    
    echo "✓ DATABASE CONNECTED!\n\n";
    echo "Products: " . $products['cnt'] . " rows\n";
    echo "Categories: " . $categories['cnt'] . " rows\n";
    
    if ($products['cnt'] > 0) {
        echo "\n✓✓✓ PRODUCTS READY! Check homepage.\n";
    }
} catch (PDOException $e) {
    echo "✗ CONNECTION FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
}
?>
