<?php
require_once __DIR__ . '/../../includes/admin_layout.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) { header('Location: ' . APP_URL . '/admin/products/'); exit; }

$errors = [];
$name = $product['name'];
$description = $product['description'];
$price = $product['price'];
$stock = $product['stock'];
$category_id = $product['category_id'];

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
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE slug = ? AND id != ?");
        $stmt->execute([$slug, $id]);
        if ($stmt->fetchColumn() > 0) $slug .= '-' . time();

        $image = $product['image'];
        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'])) {
                $image = 'product-' . time() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_PATH . 'products/' . $image);
            }
        }

        $stmt = $pdo->prepare("UPDATE products SET category_id=?, name=?, slug=?, description=?, price=?, image=?, stock=? WHERE id=?");
        $stmt->execute([$category_id, $name, $slug, $description, $price, $image, $stock, $id]);
        flash('success', 'Product updated successfully');
        header('Location: ' . APP_URL . '/admin/products/');
        exit;
    }
}

adminHeader();
?>

<div class="page-header">
    <h1>Edit Product</h1>
    <a href="<?= APP_URL ?>/admin/products/" class="btn btn-secondary btn-sm">← Back</a>
</div>

<div style="background:var(--white);padding:20px;border-radius:var(--radius);box-shadow:var(--shadow);max-width:600px">
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product Name *</label>
            <input type="text" name="name" value="<?= sanitize($name) ?>">
            <?php if (!empty($errors['name'])): ?><div class="form-error"><?= $errors['name'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Category *</label>
            <select name="category_id">
                <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $category_id == $cat['id'] ? 'selected' : '' ?>><?= sanitize($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"><?= sanitize($description) ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Price (Rp) *</label>
                <input type="number" name="price" value="<?= $price ?>" min="0">
            </div>
            <div class="form-group">
                <label>Stock *</label>
                <input type="number" name="stock" value="<?= $stock ?>" min="0">
            </div>
        </div>
        <div class="form-group">
            <label>Image (leave empty to keep current)</label>
            <?php if (!empty($product['image'])): ?>
            <div style="margin-bottom:8px"><img src="<?= APP_URL ?>/uploads/products/<?= $product['image'] ?>" alt="Current" style="max-width:120px;border-radius:8px"></div>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Update Product</button>
    </form>
</div>

<?php adminFooter(); ?>
