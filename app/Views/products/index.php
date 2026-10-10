<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-heading"><div><p class="eyebrow">Inventory</p><h1>Products</h1><p class="lead">Manage prices, product images, and available stock.</p></div><a class="button" href="<?= site_url('products/new') ?>">＋ New product</a></div>
<div class="table-card"><table><thead><tr><th>Product</th><th>Price</th><th>Stock</th><th>Added</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($products as $product): ?>
<?php $image = ! empty($product['image']) && is_file(FCPATH.'uploads'.DIRECTORY_SEPARATOR.'products'.DIRECTORY_SEPARATOR.$product['image']) ? $product['image'] : 'placeholder.svg'; ?>
<tr><td><div class="product-cell"><img class="product-thumb" src="<?= base_url('uploads/products/'.rawurlencode($image)) ?>" alt=""><span class="table-title"><?= esc($product['name']) ?></span></div></td><td class="money">₱<?= number_format((float)$product['price'],2) ?></td><td><span class="badge <?= (int)$product['stock_quantity'] <= 5 ? 'amber' : 'green' ?>"><?= number_format((int)$product['stock_quantity']) ?> in stock</span></td><td class="muted"><?= date('M j, Y', strtotime($product['created_at'])) ?></td><td><div class="actions-inline"><a class="button secondary small" href="<?= site_url('products/'.$product['id'].'/edit') ?>">Edit</a><form action="<?= site_url('products/'.$product['id'].'/delete') ?>" method="post" onsubmit="return confirm('Delete this product?')"><?= csrf_field() ?><button class="button danger small" type="submit">Delete</button></form></div></td></tr>
<?php endforeach ?>
<?php if ($products === []): ?><tr><td class="empty-state" colspan="5">No products yet. Add your first product to get started.</td></tr><?php endif ?>
</tbody></table></div>
<?= $this->endSection() ?>
