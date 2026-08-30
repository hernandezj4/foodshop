<?php
require_once __DIR__ . '/../../includes/admin_layout.php';

$errors = [];
$name = $description = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    if (empty($name)) $errors['name'] = 'Name is required';

    if (empty($errors)) {
        $slug = generateSlug($name);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetchColumn() > 0) $slug .= '-' . time();

        $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
        $stmt->execute([$name, $slug, $description]);
        flash('success', 'Category added');
        header('Location: ' . APP_URL . '/admin/categories/');
        exit;
    }
}

adminHeader();
?>

<div class="page-header">
    <h1>Add Category</h1>
    <a href="<?= APP_URL ?>/admin/categories/" class="btn btn-secondary btn-sm">← Back</a>
</div>

<div style="background:var(--white);padding:20px;border-radius:var(--radius);box-shadow:var(--shadow);max-width:500px">
    <form method="POST">
        <div class="form-group">
            <label>Category Name *</label>
            <input type="text" name="name" value="<?= sanitize($name) ?>" placeholder="Enter category name">
            <?php if (!empty($errors['name'])): ?><div class="form-error"><?= $errors['name'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3" placeholder="Category description"><?= sanitize($description) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Save Category</button>
    </form>
</div>

<?php adminFooter(); ?>
