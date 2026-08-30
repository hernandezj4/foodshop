    -- Database FoodShop
CREATE DATABASE IF NOT EXISTS foodshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE foodshop;

-- Categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products
CREATE TABLE IF NOT EXISTS products (
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(20) NOT NULL UNIQUE,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_address TEXT NOT NULL,
    customer_note TEXT,
    total_amount DECIMAL(10,0) NOT NULL,
    status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Order Items
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    product_price DECIMAL(10,0) NOT NULL,
    quantity INT NOT NULL,
    subtotal DECIMAL(10,0) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Admin Users
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed admin
INSERT INTO admins (name, email, password) VALUES 
('Admin', 'admin@foodshop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Seed categories
INSERT INTO categories (name, slug, description) VALUES 
('Makanan', 'makanan', 'Berbagai jenis makanan lezat'),
('Minuman', 'minuman', 'Minuman segar dan nikmat'),
('Snack', 'snack', 'Camilan ringan'),
('Dessert', 'dessert', 'Pemanis penutup');

-- Seed products
INSERT INTO products (category_id, name, slug, description, price, stock) VALUES 
(1, 'Nasi Goreng Spesial', 'nasi-goreng-spesial', 'Nasi goreng dengan bumbu spesial dan topping telur', 25000, 50),
(1, 'Ayam Bakar Madu', 'ayam-bakar-madu', 'Ayam bakar dengan baluran madu yang manis', 35000, 30),
(1, 'Sate Ayam', 'sate-ayam', 'Sate ayam 10 tusuk dengan bumbu kacang', 30000, 40),
(2, 'Es Teh Manis', 'es-teh-manis', 'Teh manis dingin yang menyegarkan', 8000, 100),
(2, 'Jus Alpukat', 'jus-alpukat', 'Jus alpukat segar dengan susu', 15000, 50),
(2, 'Es Jeruk', 'es-jeruk', 'Jeruk segar peras langsung', 12000, 60),
(3, 'Keripik Kentang', 'keripik-kentang', 'Keripik kentang renyah rasa original', 10000, 80),
(3, 'Kacang Mete', 'kacang-mete', 'Kacang mete panggang premium', 25000, 30),
(4, 'Pudding Coklat', 'pudding-coklat', 'Pudding coklat lembut dan nikmat', 12000, 40),
(4, 'Es Krim Vanila', 'es-krim-vanila', 'Es krim vanila lembut', 10000, 50);
