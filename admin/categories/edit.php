<?php
require_once __DIR__ . '/../../includes/admin_layout.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) { header('Location: ' . APP_URL . '/admin/categories/'); exit; }

$errors = [];
$name = $category['name'];
$description = $category['description'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    if (empty($name)) $errors['name'] = 'Name is required';

    if (empty($errors)) {
        $slug = generateSlug($name);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE slug = ? AND id != ?");
        $stmt->execute([$slug, $id]);
        if ($stmt->fetchColumn() > 0) $slug .= '-' . time();

        $stmt = $pdo->prepare("UPDATE categories SET name=?, slug=?, description=? WHERE id=?");
        $stmt->execute([$name, $slug, $description, $id]);
        flash('success', 'Category updated');
        header('Location: ' . APP_URL . '/admin/categories/');
        exit;
    }
}

adminHeader();
?>

<div class="page-header">
    <h1>Edit Category</h1>
    <a href="<?= APP_URL ?>/admin/categories/" class="btn btn-secondary btn-sm">← Back</a>
</div>

<div style="background:var(--white);padding:20px;border-radius:var(--radius);box-shadow:var(--shadow);max-width:500px">
    <form method="POST">
        <div class="form-group">
            <label>Category Name *</label>
            <input type="text" name="name" value="<?= sanitize($name) ?>">
            <?php if (!empty($errors['name'])): ?><div class="form-error"><?= $errors['name'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"><?= sanitize($description) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Update Category</button>
    </form>
</div>

<?php adminFooter(); ?>
