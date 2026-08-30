<?php
header('Content-Type: text/plain');

echo "=== DEBUG DATABASE CONNECTION ===\n\n";

// 1. Cek env vars
echo "1. ENV VARS CHECK:\n";
$host = getenv('MYSQLHOST') ?: 'NOT SET';
$port = getenv('MYSQLPORT') ?: 'NOT SET';
$dbname = getenv('MYSQLDATABASE') ?: 'NOT SET';
$user = getenv('MYSQLUSER') ?: 'NOT SET';
$pass = getenv('MYSQLPASSWORD') ? '***SET***' : 'NOT SET';

echo "  MYSQLHOST: $host\n";
echo "  MYSQLPORT: $port\n";
echo "  MYSQLDATABASE: $dbname\n";
echo "  MYSQLUSER: $user\n";
echo "  MYSQLPASSWORD: $pass\n";

// 2. Cek apakah env vars kosong (string kosong vs truly not set)
echo "\n2. ENV VAR VALUES (raw):\n";
$hostRaw = getenv('MYSQLHOST');
$portRaw = getenv('MYSQLPORT');
$dbnameRaw = getenv('MYSQLDATABASE');
$userRaw = getenv('MYSQLUSER');
$passRaw = getenv('MYSQLPASSWORD');

echo "  MYSQLHOST type: " . gettype($hostRaw) . ", value: " . var_export($hostRaw, true) . "\n";
echo "  MYSQLPORT type: " . gettype($portRaw) . ", value: " . var_export($portRaw, true) . "\n";
echo "  MYSQLDATABASE type: " . gettype($dbnameRaw) . ", value: " . var_export($dbnameRaw, true) . "\n";
echo "  MYSQLUSER type: " . gettype($userRaw) . ", value: " . var_export($userRaw, true) . "\n";
echo "  MYSQLPASSWORD type: " . gettype($passRaw) . ", value: " . (strlen($passRaw) > 0 ? 'SET (length: ' . strlen($passRaw) . ')' : 'FALSE or EMPTY') . "\n";

// 3. Gunakan logic dari database.php
echo "\n3. CONNECTION LOGIC (dari database.php):\n";
$host2 = getenv('MYSQLHOST');
$port2 = getenv('MYSQLPORT');
$dbname2 = getenv('MYSQLDATABASE');
$username2 = getenv('MYSQLUSER');
$password2 = getenv('MYSQLPASSWORD');

$isRailway = (php_uname('s') === 'Linux') && (getenv('RAILWAY_ENVIRONMENT_NAME') !== false);
echo "  Is Railway: " . ($isRailway ? 'YES' : 'NO') . "\n";

if ($isRailway) {
    $host2 = $host2 ?: 'mysql.railway.internal';
    $port2 = $port2 ?: '3306';
    $dbname2 = $dbname2 ?: 'railway';
    $username2 = $username2 ?: 'root';
    $password2 = $password2 ?: '';
}

echo "  Final Host: $host2\n";
echo "  Final Port: $port2\n";
echo "  Final Database: $dbname2\n";
echo "  Final User: $username2\n";
echo "  Final Password: " . (strlen($password2) > 0 ? '***SET***' : 'EMPTY') . "\n";

// 4. Try to connect
echo "\n4. CONNECTION ATTEMPT:\n";
try {
    $dsn = "mysql:host=$host2;port=$port2;dbname=$dbname2;charset=utf8mb4";
    echo "  DSN: $dsn\n";
    
    $pdo = new PDO($dsn, $username2, $password2, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    echo "  ✓ CONNECTION SUCCESSFUL!\n";
    
    // 5. Cek tables
    echo "\n5. DATABASE TABLES:\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    if (empty($tables)) {
        echo "  ✗ NO TABLES FOUND\n";
    } else {
        foreach ($tables as $table) {
            $count = $pdo->query("SELECT COUNT(*) as cnt FROM $table")->fetch()['cnt'];
            echo "  - $table: $count rows\n";
        }
    }
    
} catch (PDOException $e) {
    echo "  ✗ CONNECTION FAILED\n";
    echo "  Error: " . $e->getMessage() . "\n";
    echo "  Code: " . $e->getCode() . "\n";
}
?>
