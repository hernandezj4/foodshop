<?php
$host = 'mysql.railway.internal';
$dbname = 'railway';
$username = 'root';
$password = 'dAqtiJKLwNSNlqsUlZCokdRstsvblczs';
$port = '3306';

header('Content-Type: text/plain');

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    $products = $pdo->query("SELECT COUNT(*) as cnt FROM products")->fetch();
    $categories = $pdo->query("SELECT COUNT(*) as cnt FROM categories")->fetch();
    
    echo "✓ DATABASE CONNECTED OK\n\n";
    echo "Products count: " . $products['cnt'] . "\n";
    echo "Categories count: " . $categories['cnt'] . "\n";
    
    if ($products['cnt'] > 0) {
        echo "\n✓ Products ada! Sekarang check homepage.\n";
        echo "\nDaftar Products:\n";
        $all = $pdo->query("SELECT id, name, price FROM products")->fetchAll();
        foreach ($all as $p) {
            echo "- " . $p['name'] . " (Rp " . number_format($p['price']) . ")\n";
        }
    } else {
        echo "\n✗ Products KOSONG! Database belum di-seed.\n";
    }
} catch (PDOException $e) {
    echo "✗ DATABASE CONNECTION FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
}
?>
