<?php
require_once __DIR__ . '/../../includes/admin_layout.php';

$errors = [];
$name = $description = $price = $stock = $category_id = '';

$categories = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (int)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $category_id = (int)($_POST['category_id'] ?? 0);

    if (empty($name)) $errors['name'] = 'Name is required';
    if ($price <= 0) $errors['price'] = 'Price must be > 0';
    if ($category_id <= 0) $errors['category_id'] = 'Category is required';

    if (empty($errors)) {
        $slug = generateSlug($name);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetchColumn() > 0) $slug .= '-' . time();

        $image = '';
        if (!empty($_FILES['image']['name'])) {
            if ($_FILES['image']['size'] > 5*1024*1024) {
                $errors['image'] = 'Image max 5MB';
            } else {
                $imgInfo = @getimagesize($_FILES['image']['tmp_name']);
                if (!$imgInfo) {
                    $errors['image'] = 'Invalid image file';
                } else {
                    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                        $image = 'product-' . time() . '.' . $ext;
                        move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_PATH . 'products/' . $image);
                    } else {
                        $errors['image'] = 'Invalid image format';
                    }
                }
            }
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare("INSERT INTO products (category_id, name, slug, description, price, image, stock) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$category_id, $name, $slug, $description, $price, $image, $stock]);
            flash('success', 'Product added successfully');
            header('Location: ' . APP_URL . '/admin/products/');
            exit;
        }
    }
}

adminHeader();
?>

<div class="page-header">
    <h1>Add Product</h1>
    <a href="<?= APP_URL ?>/admin/products/" class="btn btn-secondary btn-sm">← Back</a>
</div>

<div style="background:var(--white);padding:20px;border-radius:var(--radius);box-shadow:var(--shadow);max-width:600px">
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product Name *</label>
            <input type="text" name="name" value="<?= sanitize($name) ?>" placeholder="Enter product name">
            <?php if (!empty($errors['name'])): ?><div class="form-error"><?= $errors['name'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Category *</label>
            <select name="category_id">
                <option value="">-- Select Category --</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $category_id == $cat['id'] ? 'selected' : '' ?>><?= sanitize($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['category_id'])): ?><div class="form-error"><?= $errors['category_id'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3" placeholder="Product description"><?= sanitize($description) ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Price (Rp) *</label>
                <input type="number" name="price" value="<?= $price ?>" min="0">
                <?php if (!empty($errors['price'])): ?><div class="form-error"><?= $errors['price'] ?></div><?php endif; ?>
            </div>
            <div class="form-group">
                <label>Stock *</label>
                <input type="number" name="stock" value="<?= $stock ?>" min="0">
            </div>
        </div>
        <div class="form-group">
            <label>Image</label>
            <input type="file" name="image" accept="image/*">
            <?php if (!empty($errors['image'])): ?><div class="form-error"><?= $errors['image'] ?></div><?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Save Product</button>
    </form>
</div>

<?php adminFooter(); ?>
