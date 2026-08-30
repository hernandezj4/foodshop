<?php
header('Content-Type: text/plain');

echo "=== RAILWAY ENV VARS CHECK ===\n\n";

// Use same logic as database.php
$host = ($_ENV['MYSQLHOST'] ?? null) ?: (getenv('MYSQLHOST') ?: null) ?: 'mysql.railway.internal';
$port = ($_ENV['MYSQLPORT'] ?? null) ?: (getenv('MYSQLPORT') ?: null) ?: '3306';
$dbname = ($_ENV['MYSQLDATABASE'] ?? null) ?: (getenv('MYSQLDATABASE') ?: null) ?: 'railway';
$username = ($_ENV['MYSQLUSER'] ?? null) ?: (getenv('MYSQLUSER') ?: null) ?: 'root';
$password = ($_ENV['MYSQLPASSWORD'] ?? null) ?: (getenv('MYSQLPASSWORD') ?: null) ?: 'dAqtiJKLwNSNlqsUkdRStSvblczS';

echo "MYSQLHOST: $host\n";
echo "MYSQLPORT: $port\n";
echo "MYSQLDATABASE: $dbname\n";
echo "MYSQLUSER: $username\n";
echo "MYSQLPASSWORD: " . (strlen($password) > 0 ? '***SET***' : 'NOT SET') . "\n\n";

if (strlen($host) === 0) {
    echo "✗ HOST NOT SET - Railway env vars tidak terdeteksi!\n";
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
