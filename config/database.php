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
} catch (PDOException $e) {
    $pdo = null;
}
