<?php
require_once __DIR__ . '/../../includes/admin_layout.php';
$status=$_GET['status']??'';
$sql="SELECT * FROM orders";$p=[];
if($status){$sql.=" WHERE status=?";$p[]=$status;}
$sql.=" ORDER BY created_at DESC";
$stmt=$pdo->prepare($sql);$stmt->execute($p);$orders=$stmt->fetchAll();
$statuses=['pending','confirmed','processing','shipped','delivered','cancelled'];
adminHeader();
?>
<div class="page-header"><h1>📋 Orders</h1></div>
<div class="filter-scroll">
<a href="<?= APP_URL ?>/admin/orders/" class="filter-btn-pill <?= !$status?'active':'' ?>">All</a>
<?php foreach($statuses as $s): ?><a href="?status=<?= $s ?>" class="filter-btn-pill <?= $status===$s?'active':'' ?>"><?= ucfirst($s) ?></a><?php endforeach; ?>
</div>
<div class="tw"><table>
<thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
<tbody>
<?php if(empty($orders)): ?><tr><td colspan="6" style="text-align:center;color:var(--gray);padding:24px">No orders</td></tr>
<?php else: foreach($orders as $o): ?>
<tr>
<td style="font-weight:600"><?= $o['order_number'] ?></td>
<td><?= sanitize($o['customer_name']) ?></td>
<td style="font-weight:600;color:var(--red)">Rp <?= number_format($o['total_amount'],0,',','.') ?></td>
<td><span class="sb sb-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
<td><?= date('d M Y',strtotime($o['created_at'])) ?></td>
<td><a href="<?= APP_URL ?>/admin/orders/detail.php?id=<?= $o['id'] ?>" class="btn btn-sm btn-secondary">Detail</a></td>
</tr>
<?php endforeach; endif; ?>
</tbody></table></div>
<?php adminFooter(); ?>
