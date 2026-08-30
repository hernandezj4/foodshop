<?php
header('Content-Type: text/plain');
echo "=== DB DEBUG ===\n";
echo "DB_HOST: " . (getenv('DB_HOST') ?: 'NOT SET') . "\n";
echo "DB_NAME: " . (getenv('DB_NAME') ?: 'NOT SET') . "\n";
echo "DB_USER: " . (getenv('DB_USER') ?: 'NOT SET') . "\n";
echo "DB_PASS: " . (getenv('DB_PASS') ? 'SET (hidden)' : 'NOT SET') . "\n";
echo "MYSQLHOST: " . (getenv('MYSQLHOST') ?: 'NOT SET') . "\n";
echo "MYSQLDATABASE: " . (getenv('MYSQLDATABASE') ?: 'NOT SET') . "\n";
echo "MYSQLUSER: " . (getenv('MYSQLUSER') ?: 'NOT SET') . "\n";
echo "MYSQLPASSWORD: " . (getenv('MYSQLPASSWORD') ? 'SET (hidden)' : 'NOT SET') . "\n";
echo "MYSQLPORT: " . (getenv('MYSQLPORT') ?: 'NOT SET') . "\n";
echo "================\n";

$host = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: 'railway';
$username = getenv('DB_USER') ?: getenv('MYSQLUSER') ?: 'root';
$password = getenv('DB_PASS') ?: getenv('MYSQLPASSWORD') ?: '';
$port = getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: '3306';

echo "Connecting to: host=$host port=$port db=$dbname user=$username\n";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "DB CONNECTED OK!\n";
    $rows = $pdo->query("SELECT COUNT(*) as cnt FROM products")->fetch();
    echo "Products count: " . $rows['cnt'] . "\n";
} catch (PDOException $e) {
    echo "DB ERROR: " . $e->getMessage() . "\n";
}
