<?php
require_once __DIR__ . '/../../includes/admin_layout.php';
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products WHERE is_active=1")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pendingOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status='pending'")->fetchColumn();
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE status!='cancelled'")->fetchColumn();
adminHeader();
?>
<div class="page-header"><h1>📊 Dashboard</h1><span style="font-size:12px;color:var(--gray)">Welcome, <?= sanitize($_SESSION['admin_name']??'Admin') ?></span></div>
<div class="admin-stats">
    <div class="stat-card s-primary"><h3>Products</h3><div class="sv"><?= $totalProducts ?></div></div>
    <div class="stat-card s-success"><h3>Orders</h3><div class="sv"><?= $totalOrders ?></div></div>
    <div class="stat-card s-warning"><h3>Pending</h3><div class="sv"><?= $pendingOrders ?></div></div>
    <div class="stat-card s-info"><h3>Revenue</h3><div class="sv">Rp <?= number_format($totalRevenue,0,',','.') ?></div></div>
</div>
<div class="tw"><table>
<thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
<tbody>
<?php foreach ($pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5")->fetchAll() as $o): ?>
<tr>
    <td style="font-weight:600"><?= $o['order_number'] ?></td>
    <td><?= sanitize($o['customer_name']) ?></td>
    <td style="font-weight:600">Rp <?= number_format($o['total_amount'],0,',','.') ?></td>
    <td><span class="sb sb-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
    <td><?= date('d M Y',strtotime($o['created_at'])) ?></td>
</tr>
<?php endforeach; ?>
</tbody></table></div>
<?php adminFooter(); ?>
