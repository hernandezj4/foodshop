<?php
// Read from actual env vars - Railway sets these automatically
// Treat empty strings as not set
$host = ($_ENV['MYSQLHOST'] ?? null) ?: (getenv('MYSQLHOST') ?: null);
$port = ($_ENV['MYSQLPORT'] ?? null) ?: (getenv('MYSQLPORT') ?: null) ?: '3306';
$dbname = ($_ENV['MYSQLDATABASE'] ?? null) ?: (getenv('MYSQLDATABASE') ?: null);
$username = ($_ENV['MYSQLUSER'] ?? null) ?: (getenv('MYSQLUSER') ?: null);
$password = ($_ENV['MYSQLPASSWORD'] ?? null) ?: (getenv('MYSQLPASSWORD') ?: null);

// If running on Railway (check if not localhost), use Railway MySQL defaults
$isRailway = (php_uname('s') === 'Linux' && !$host);
if ($isRailway) {
    $host = $host ?: 'mysql.railway.internal';
    $dbname = $dbname ?: 'railway';
    $username = $username ?: 'root';
    // DO NOT use hardcoded password - Railway MUST provide it via MYSQLPASSWORD
    $password = $password ?: '';
} else {
    // Local development defaults
    $host = $host ?: 'localhost';
    $dbname = $dbname ?: 'foodshop';
    $username = $username ?: 'root';
    $password = $password ?: '';
}

$pdo = null;

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    // Create tables if they don't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(100) NOT NULL UNIQUE,
        description TEXT,
        image VARCHAR(255),
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT NOT NULL,
        name VARCHAR(200) NOT NULL,
        slug VARCHAR(200) NOT NULL UNIQUE,
        description TEXT,
        price DECIMAL(10,0) NOT NULL,
        image VARCHAR(255),
        stock INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Seed categories if empty
    $catCount = $pdo->query("SELECT COUNT(*) as cnt FROM categories")->fetch()['cnt'];
    if ($catCount == 0) {
        $pdo->exec("INSERT INTO categories (name, slug, description) VALUES 
        ('Makanan', 'makanan', 'Berbagai jenis makanan lezat'),
        ('Minuman', 'minuman', 'Minuman segar dan nikmat'),
        ('Snack', 'snack', 'Camilan ringan'),
        ('Dessert', 'dessert', 'Pemanis penutup')");
    }
    
    // Seed products if empty
    $prodCount = $pdo->query("SELECT COUNT(*) as cnt FROM products")->fetch()['cnt'];
    if ($prodCount == 0) {
        $pdo->exec("INSERT INTO products (category_id, name, slug, description, price, stock, is_active) VALUES 
        (1, 'Nasi Goreng Kampung', 'nasi-goreng-kampung', 'Nasi goreng dengan bumbu khas kampung, telur, dan sayuran', 22000, 50, 1),
        (1, 'Mie Ayam Spesial', 'mie-ayam-spesial', 'Mie ayam dengan kuah gurih dan daging ayam pilihan', 18000, 40, 1),
        (2, 'Es Jeruk Segar', 'es-jeruk-segar', 'Jeruk segar peras langsung dengan gula aren', 10000, 100, 1),
        (2, 'Kopi Susu Kental', 'kopi-susu-kental', 'Kopi premium dengan susu kental manis', 12000, 60, 1),
        (3, 'Tahu Goreng Crispy', 'tahu-goreng-crispy', 'Tahu goreng renyah dengan sambal pedas', 8000, 80, 1),
        (4, 'Martabak Manis Coklat', 'martabak-manis-coklat', 'Martabak tebal dengan coklat lumer dan keju', 15000, 30, 1),
        (4, 'Pisang Goreng Madu', 'pisang-goreng-madu', 'Pisang goreng dengan sirup madu dan keju', 12000, 50, 1)");
    }
    
    // Seed admin if empty
    $adminCount = $pdo->query("SELECT COUNT(*) as cnt FROM admins")->fetch()['cnt'];
    if ($adminCount == 0) {
        $pdo->exec("INSERT INTO admins (name, email, password) VALUES 
        ('Admin', 'admin@foodshop.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')");
    }
} catch (PDOException $e) {
    $pdo = null;
}
