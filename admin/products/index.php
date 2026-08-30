<?php
require_once __DIR__ . '/../../includes/admin_layout.php';
if (isset($_GET['delete'])) { $id=(int)$_GET['delete']; $pdo->prepare("UPDATE products SET is_active=0 WHERE id=?")->execute([$id]); flash('success','Deleted'); header('Location:'.APP_URL.'/admin/products/'); exit; }
$success = flash('success');
$products = $pdo->query("SELECT p.*,c.name as category_name FROM products p JOIN categories c ON p.category_id=c.id ORDER BY p.created_at DESC")->fetchAll();
adminHeader();
?>
<div class="page-header"><h1>📦 Products</h1><a href="<?= APP_URL ?>/admin/products/create.php" class="btn btn-primary btn-sm btn-round">+ Add</a></div>
<?php if ($success): ?><div class="toast toast-success" style="position:static;margin-bottom:14px"><?= $success ?></div><?php endif; ?>
<div class="tw"><table>
<thead><tr><th>#</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr></thead>
<tbody><?php foreach ($products as $i=>$p): ?>
<tr>
<td><?= $i+1 ?></td>
<td style="font-weight:500"><?= sanitize($p['name']) ?></td>
<td><?= $p['category_name'] ?></td>
<td style="font-weight:600;color:var(--red)">Rp <?= number_format($p['price'],0,',','.') ?></td>
<td><?= $p['stock'] ?></td>
<td><span class="sb <?= $p['is_active']?'sb-delivered':'sb-cancelled' ?>"><?= $p['is_active']?'Active':'Inactive' ?></span></td>
<td><a href="<?= APP_URL ?>/admin/products/edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-secondary">Edit</a> <a href="?delete=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Del</a></td>
</tr>
<?php endforeach; ?></tbody></table></div>
<?php adminFooter(); ?>
