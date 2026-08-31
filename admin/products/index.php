<?php
require_once __DIR__ . '/../../includes/admin_layout.php';
if (isset($_GET['delete'])) { $id=(int)$_GET['delete']; $pdo->prepare("UPDATE products SET is_active=0 WHERE id=?")->execute([$id]); flash('success','Deleted'); header('Location:'.APP_URL.'/admin/products/'); exit; }
if (isset($_GET['restore'])) { $id=(int)$_GET['restore']; $pdo->prepare("UPDATE products SET is_active=1 WHERE id=?")->execute([$id]); flash('success','Restored'); header('Location:'.APP_URL.'/admin/products/'); exit; }
$success = flash('success');
$filter = $_GET['filter'] ?? 'all';
$sql = "SELECT p.*,c.name as category_name FROM products p JOIN categories c ON p.category_id=c.id";
if ($filter === 'inactive') $sql .= " WHERE p.is_active=0";
elseif ($filter === 'active') $sql .= " WHERE p.is_active=1";
$sql .= " ORDER BY p.created_at DESC";
$products = $pdo->query($sql)->fetchAll();

function adminProductImage($p) {
    if (!empty($p['image'])) {
        if (strpos($p['image'], 'http') === 0) return $p['image'];
        return APP_URL . '/uploads/products/' . $p['image'];
    }
    return 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=80&h=80&fit=crop';
}
adminHeader();
?>
<div class="page-header"><h1>📦 Products (<?= count($products) ?>)</h1><a href="<?= APP_URL ?>/admin/products/create.php" class="btn btn-primary btn-sm btn-round">+ Add Product</a></div>
<?php if ($success): ?><div class="toast toast-success" style="position:static;margin-bottom:14px"><?= $success ?></div><?php endif; ?>

<div style="display:flex;gap:6px;margin-bottom:14px;flex-wrap:wrap">
    <a href="?filter=all" class="filter-btn-pill <?= $filter==='all'?'active':'' ?>">All</a>
    <a href="?filter=active" class="filter-btn-pill <?= $filter==='active'?'active':'' ?>">Active</a>
    <a href="?filter=inactive" class="filter-btn-pill <?= $filter==='inactive'?'active':'' ?>">Inactive</a>
</div>

<div class="tw"><table>
<thead><tr><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr></thead>
<tbody><?php foreach ($products as $i=>$p): ?>
<tr>
<td><img src="<?= adminProductImage($p) ?>" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:8px"></td>
<td style="font-weight:500"><?= sanitize($p['name']) ?></td>
<td><?= $p['category_name'] ?></td>
<td style="font-weight:600;color:var(--red)">Rp <?= number_format($p['price'],0,',','.') ?></td>
<td><?= $p['stock'] ?></td>
<td><span class="sb <?= $p['is_active']?'sb-delivered':'sb-cancelled' ?>"><?= $p['is_active']?'Active':'Inactive' ?></span></td>
<td>
    <a href="<?= APP_URL ?>/admin/products/edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
    <?php if ($p['is_active']): ?>
    <a href="?delete=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deactivate this product?')">Del</a>
    <?php else: ?>
    <a href="?restore=<?= $p['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Restore this product?')">Restore</a>
    <?php endif; ?>
</td>
</tr>
<?php endforeach; ?></tbody></table></div>
<?php adminFooter(); ?>
