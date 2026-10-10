<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-heading"><div><p class="eyebrow">Transactions</p><h1>Sales History</h1><p class="lead">A complete record of purchases, customers, and staff.</p></div><a class="button" href="<?= site_url('sales/new') ?>">＋ Record sale</a></div>
<div class="table-card"><table><thead><tr><th>Date</th><th>Product</th><th>Customer</th><th>Staff</th><th>Qty</th><th>Total</th></tr></thead><tbody>
<?php foreach($sales as $sale): ?><tr><td><span class="table-title"><?= date('M j, Y',strtotime($sale['created_at'])) ?></span><br><small class="muted"><?= date('g:i A',strtotime($sale['created_at'])) ?></small></td><td><?= esc($sale['product_name']) ?></td><td><?= esc($sale['customer_name']??'Walk-in customer') ?></td><td><?= esc($sale['staff_name']) ?></td><td><span class="badge"><?= number_format((int)$sale['quantity']) ?></span></td><td class="money">₱<?= number_format((float)$sale['total_price'],2) ?></td></tr><?php endforeach ?>
<?php if($sales===[]): ?><tr><td class="empty-state" colspan="6">No sales recorded yet.</td></tr><?php endif ?>
</tbody></table></div>
<?= $this->endSection() ?>
