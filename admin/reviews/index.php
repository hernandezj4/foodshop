<?php
require_once __DIR__ . '/../../includes/admin_layout.php';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM reviews WHERE id = ?")->execute([$id]);
    flash('success', 'Review deleted');
    header('Location: ' . APP_URL . '/admin/reviews/');
    exit;
}

if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $r = $pdo->prepare("SELECT is_active FROM reviews WHERE id = ?");
    $r->execute([$id]);
    $rev = $r->fetch();
    if ($rev) {
        $newStatus = $rev['is_active'] ? 0 : 1;
        $pdo->prepare("UPDATE reviews SET is_active = ? WHERE id = ?")->execute([$newStatus, $id]);
        flash('success', $newStatus ? 'Review shown' : 'Review hidden');
    }
    header('Location: ' . APP_URL . '/admin/reviews/');
    exit;
}

$success = flash('success');
$filter = $_GET['filter'] ?? 'all';

$sql = "SELECT r.*, p.name as product_name FROM reviews r JOIN products p ON r.product_id = p.id";
if ($filter === 'hidden') $sql .= " WHERE r.is_active = 0";
elseif ($filter === 'shown') $sql .= " WHERE r.is_active = 1";
$sql .= " ORDER BY r.created_at DESC";
$reviews = $pdo->query($sql)->fetchAll();
adminHeader();
?>
<div class="page-header"><h1>⭐ Reviews (<?= count($reviews) ?>)</h1></div>
<?php if ($success): ?><div class="toast toast-success" style="position:static;margin-bottom:14px"><?= $success ?></div><?php endif; ?>

<div style="display:flex;gap:6px;margin-bottom:14px;flex-wrap:wrap">
    <a href="?filter=all" class="filter-btn-pill <?= $filter==='all'?'active':'' ?>">All</a>
    <a href="?filter=shown" class="filter-btn-pill <?= $filter==='shown'?'active':'' ?>">Shown</a>
    <a href="?filter=hidden" class="filter-btn-pill <?= $filter==='hidden'?'active':'' ?>">Hidden</a>
</div>

<div class="tw"><table>
<thead><tr><th>Customer</th><th>Kelas</th><th>Product</th><th>Rating</th><th>Review</th><th>Status</th><th>Actions</th></tr></thead>
<tbody><?php foreach ($reviews as $r): ?>
<tr>
<td style="font-weight:500"><?= sanitize($r['customer_name']) ?></td>
<td><?= sanitize($r['kelas']) ?></td>
<td><?= sanitize($r['product_name']) ?></td>
<td style="color:#f5a623"><?= str_repeat('★', $r['rating']) ?><?= str_repeat('☆', 5 - $r['rating']) ?></td>
<td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= sanitize($r['review_text']) ?></td>
<td><span class="sb <?= $r['is_active']?'sb-delivered':'sb-cancelled' ?>"><?= $r['is_active']?'Shown':'Hidden' ?></span></td>
<td>
    <a href="?toggle=<?= $r['id'] ?>" class="btn btn-sm btn-secondary" onclick="return confirm('Toggle visibility?')"><?= $r['is_active']?'Hide':'Show' ?></a>
    <a href="?delete=<?= $r['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this review permanently?')">Del</a>
</td>
</tr>
<?php endforeach; ?></tbody></table></div>
<?php adminFooter(); ?>
