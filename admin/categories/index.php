<?php
require_once __DIR__ . '/../../includes/admin_layout.php';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("UPDATE categories SET is_active = 0 WHERE id = ?");
    $stmt->execute([$id]);
    flash('success', 'Category deleted');
    header('Location: ' . APP_URL . '/admin/categories/');
    exit;
}

$success = flash('success');
$categories = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM products WHERE category_id = c.id AND is_active = 1) as product_count FROM categories c ORDER BY c.name")->fetchAll();

adminHeader();
?>

<div class="page-header">
    <h1>🏷️ Categories</h1>
    <a href="<?= APP_URL ?>/admin/categories/create.php" class="btn btn-primary btn-sm">+ Add Category</a>
</div>

<?php if ($success): ?>
<div class="toast toast-success" style="position:static;margin-bottom:16px"><?= $success ?></div>
<?php endif; ?>

<div class="tw">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Products</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $i => $cat): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td style="font-weight:500"><?= sanitize($cat['name']) ?></td>
                <td><?= $cat['slug'] ?></td>
                <td><?= $cat['product_count'] ?></td>
                <td><span class="sb <?= $cat['is_active'] ? 'sb-delivered' : 'sb-cancelled' ?>"><?= $cat['is_active'] ? 'Active' : 'Inactive' ?></span></td>
                <td>
                    <a href="<?= APP_URL ?>/admin/categories/edit.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                    <a href="?delete=<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php adminFooter(); ?>
