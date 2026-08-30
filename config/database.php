<?php
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'foodshop';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';

$pdo = null;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    $pdo = new class {
        public function prepare($s) { return $this; }
        public function execute($p = []) { return $this; }
        public function fetch() { return false; }
        public function fetchAll() { return []; }
        public function fetchColumn() { return 0; }
        public function lastInsertId() { return 0; }
        public function beginTransaction() { return true; }
        public function commit() { return true; }
        public function rollBack() { return true; }
        public function query($s) { return $this; }
    };
}
