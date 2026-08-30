<?php
$host = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: 'mysql.railway.internal';
$dbname = getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: 'railway';
$username = getenv('DB_USER') ?: getenv('MYSQLUSER') ?: 'root';
$password = getenv('DB_PASS') ?: getenv('MYSQLPASSWORD') ?: 'dAqtiJKLwNSNlqsUlZCokdRstsvblczs';
$port = getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: '3306';

$pdo = null;

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    // Auto-seed if products table is empty
    if ($pdo) {
        $count = $pdo->query("SELECT COUNT(*) as cnt FROM products")->fetch()['cnt'];
        if ($count == 0) {
            $pdo->exec("INSERT INTO products (category_id, name, slug, description, price, stock, is_active) VALUES 
            (1, 'Nasi Goreng Spesial', 'nasi-goreng-spesial', 'Nasi goreng dengan bumbu spesial', 25000, 50, 1),
            (1, 'Ayam Bakar', 'ayam-bakar', 'Ayam bakar madu lezat', 35000, 30, 1),
            (2, 'Es Teh Manis', 'es-teh-manis', 'Teh manis dingin segar', 8000, 100, 1),
            (2, 'Jus Alpukat', 'jus-alpukat', 'Jus alpukat segar', 15000, 50, 1),
            (3, 'Keripik Kentang', 'keripik-kentang', 'Keripik renyah', 10000, 80, 1),
            (4, 'Pudding Coklat', 'pudding-coklat', 'Pudding lembut', 12000, 40, 1),
            (4, 'Es Krim Vanila', 'es-krim-vanila', 'Es krim vanila', 10000, 50, 1)");
        }
    }
} catch (PDOException $e) {
    $pdo = null;
}
