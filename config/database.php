<?php
// Read from actual env vars - Railway sets these automatically
$host = $_ENV['MYSQLHOST'] ?? getenv('MYSQLHOST');
$port = $_ENV['MYSQLPORT'] ?? getenv('MYSQLPORT');
$dbname = $_ENV['MYSQLDATABASE'] ?? getenv('MYSQLDATABASE');
$username = $_ENV['MYSQLUSER'] ?? getenv('MYSQLUSER');
$password = $_ENV['MYSQLPASSWORD'] ?? getenv('MYSQLPASSWORD');

// Detect if running on Railway
$isRailway = (php_uname('s') === 'Linux') && (getenv('RAILWAY_ENVIRONMENT_NAME') !== false);

if ($isRailway) {
    // On Railway - use Railway MySQL service defaults
    $host = $host ?: 'mysql.railway.internal';
    $port = $port ?: '3306';
    $dbname = $dbname ?: 'railway';
    $username = $username ?: 'root';
    // Password MUST come from env var on Railway - if not set, connection will fail
    $password = $password ?: '';
} else {
    // Local development
    $host = $host ?: 'localhost';
    $port = $port ?: '3306';
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
    
    // Add image column to products if missing
    try { $pdo->exec("ALTER TABLE products ADD COLUMN image VARCHAR(255) DEFAULT NULL AFTER price"); } catch (Exception $e) {}

    // Seed products ONLY if table is empty
    $prodCount = $pdo->query("SELECT COUNT(*) as cnt FROM products")->fetch()['cnt'];
    if ($prodCount == 0) {
        $pdo->exec("INSERT INTO products (category_id, name, slug, description, price, image, stock, is_active) VALUES 
        (1, 'Nasi Goreng Spesial', 'nasi-goreng-spesial', 'Nasi goreng dengan bumbu spesial dan topping telur mata sapi', 25000, 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=500&h=500&fit=crop', 50, 1),
        (1, 'Ayam Bakar Madu', 'ayam-bakar-madu', 'Ayam bakar dengan baluran madu dan rempah pilihan', 35000, 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?w=500&h=500&fit=crop', 40, 1),
        (1, 'Sate Ayam', 'sate-ayam', 'Sate ayam 10 tusuk dengan bumbu kacang yang gurih', 30000, 'https://images.unsplash.com/photo-1529692236671-f1f6cf9683ba?w=500&h=500&fit=crop', 45, 1),
        (1, 'Rendang Daging', 'rendang-daging', 'Rendang daging sapi dengan santan dan rempah tradisional', 40000, 'https://images.unsplash.com/photo-1525755662778-989d0524087e?w=500&h=500&fit=crop', 30, 1),
        (2, 'Es Teh Manis', 'es-teh-manis', 'Teh manis dingin yang menyegarkan dengan es batu', 8000, 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=500&h=500&fit=crop', 100, 1),
        (2, 'Jus Alpukat', 'jus-alpukat', 'Jus alpukat segar dengan susu kental yang creamy', 15000, 'https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?w=500&h=500&fit=crop', 60, 1),
        (2, 'Es Jeruk', 'es-jeruk', 'Jeruk segar peras langsung dengan es dan gula aren', 12000, 'https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?w=500&h=500&fit=crop', 80, 1)");
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
