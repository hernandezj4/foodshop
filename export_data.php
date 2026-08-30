<?php
$pdo = new PDO("mysql:host=localhost;dbname=foodshop", "root", "");

$products = $pdo->query("SELECT * FROM products ORDER BY id")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories ORDER BY id")->fetchAll();
$admins = $pdo->query("SELECT * FROM admins ORDER BY id")->fetchAll();

echo "-- Copy paste semua ini ke Railway MySQL Console\n\n";

// Categories
echo "INSERT INTO categories (id, name, slug, description, is_active) VALUES\n";
foreach ($categories as $i => $cat) {
    echo "(" . $cat['id'] . ", '" . addslashes($cat['name']) . "', '" . addslashes($cat['slug']) . "', '" . addslashes($cat['description']) . "', " . $cat['is_active'] . ")";
    echo ($i < count($categories) - 1) ? ",\n" : ";\n\n";
}

// Products
echo "INSERT INTO products (id, category_id, name, slug, description, price, stock, is_active) VALUES\n";
foreach ($products as $i => $prod) {
    echo "(" . $prod['id'] . ", " . $prod['category_id'] . ", '" . addslashes($prod['name']) . "', '" . addslashes($prod['slug']) . "', '" . addslashes($prod['description']) . "', " . $prod['price'] . ", " . $prod['stock'] . ", " . $prod['is_active'] . ")";
    echo ($i < count($products) - 1) ? ",\n" : ";\n\n";
}

// Admins
echo "INSERT INTO admins (id, name, email, password) VALUES\n";
foreach ($admins as $i => $admin) {
    echo "(" . $admin['id'] . ", '" . addslashes($admin['name']) . "', '" . addslashes($admin['email']) . "', '" . addslashes($admin['password']) . "')";
    echo ($i < count($admins) - 1) ? ",\n" : ";\n\n";
}

echo "-- Done!\n";
?>
