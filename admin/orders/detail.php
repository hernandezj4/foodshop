<?php
require_once __DIR__ . '/../../includes/admin_layout.php';
$id=(int)($_GET['id']??0);
$o=$pdo->prepare("SELECT * FROM orders WHERE id=?");$o->execute([$id]);$order=$o->fetch();
if(!$order){header('Location:'.APP_URL.'/admin/orders/');exit;}
$items=$pdo->prepare("SELECT * FROM order_items WHERE order_id=?");$items->execute([$id]);$items=$items->fetchAll();
$statuses=['pending','confirmed','processing','shipped','delivered','cancelled'];
$success=flash('success');
if($_SERVER['REQUEST_METHOD']==='POST'){ $ns=$_POST['status']??''; if(in_array($ns,$statuses)){ $pdo->prepare("UPDATE orders SET status=? WHERE id=?")->execute([$ns,$id]); flash('success','Updated'); header('Location:'.APP_URL.'/admin/orders/detail.php?id='.$id); exit; } }
adminHeader();
?>
<div class="page-header"><h1>#<?= $order['order_number'] ?></h1><a href="<?= APP_URL ?>/admin/orders/" class="btn btn-secondary btn-sm">← Back</a></div>
<?php if($success): ?><div class="toast toast-success" style="position:static;margin-bottom:14px"><?= $success ?></div><?php endif; ?>
<div class="ck-card"><h3>Items</h3>
<table style="width:100%"><thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
<tbody><?php foreach($items as $it): ?><tr><td><?= sanitize($it['product_name']) ?></td><td>Rp <?= number_format($it['product_price'],0,',','.') ?></td><td><?= $it['quantity'] ?></td><td style="font-weight:600">Rp <?= number_format($it['subtotal'],0,',','.') ?></td></tr><?php endforeach; ?></tbody></table>
<div style="text-align:right;padding-top:8px;border-top:1px solid var(--gray-light);margin-top:8px"><strong>Total: <span style="color:var(--red)">Rp <?= number_format($order['total_amount'],0,',','.') ?></span></strong></div>
</div>
<div class="ck-card"><h3>Customer</h3>
<p style="font-size:12px;margin-bottom:3px"><strong>Name:</strong> <?= sanitize($order['customer_name']) ?></p>
<p style="font-size:12px;margin-bottom:3px"><strong>Phone:</strong> <?= $order['customer_phone'] ?></p>
<p style="font-size:12px;margin-bottom:3px"><strong>Address:</strong> <?= sanitize($order['customer_address']) ?></p>
<?php if($order['customer_note']): ?><p style="font-size:12px;color:var(--gray)"><strong>Note:</strong> <?= sanitize($order['customer_note']) ?></p><?php endif; ?>
</div>
<div class="ck-card"><h3>Update Status</h3>
<form method="POST" style="display:flex;gap:8px;align-items:end">
<div class="form-group" style="flex:1;margin:0"><select name="status"><?php foreach($statuses as $s): ?><option value="<?= $s ?>" <?= $order['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option><?php endforeach; ?></select></div>
<button type="submit" class="btn btn-primary btn-sm btn-round" style="height:34px">Update</button>
</form></div>
<?php adminFooter(); ?>
