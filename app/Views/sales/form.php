<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-heading"><div><p class="eyebrow">Checkout</p><h1>Record a Sale</h1><p class="lead">Select a product, choose an optional customer, and enter the quantity.</p></div><a class="button secondary" href="<?= site_url('sales') ?>">View history</a></div>
<section class="form-card">
<?php if($errors=session('errors')): ?><div class="errors"><strong>Please check the form.</strong><ul><?php foreach($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul></div><?php endif ?>
<form action="<?= site_url('sales') ?>" method="post"><?= csrf_field() ?><div class="form-grid">
<div class="field full"><label for="product_id">Product *</label><select id="product_id" name="product_id" required><option value="">Choose a product</option><?php foreach($products as $product): ?><option value="<?= $product['id'] ?>" <?= old('product_id')==(string)$product['id']?'selected':'' ?>><?= esc($product['name']) ?> — ₱<?= number_format((float)$product['price'],2) ?> (<?= $product['stock_quantity'] ?> available)</option><?php endforeach ?></select><?php if($products===[]): ?><p class="hint">No in-stock products are available. Add inventory first.</p><?php endif ?></div>
<div class="field"><label for="customer_id">Customer</label><select id="customer_id" name="customer_id"><option value="">Walk-in customer</option><?php foreach($customers as $customer): ?><option value="<?= $customer['id'] ?>" <?= old('customer_id')==(string)$customer['id']?'selected':'' ?>><?= esc($customer['full_name']) ?></option><?php endforeach ?></select></div>
<div class="field"><label for="quantity">Quantity *</label><input id="quantity" name="quantity" type="number" min="1" step="1" value="<?= esc(old('quantity','1'),'attr') ?>" required></div>
</div><div class="form-actions"><button class="button" type="submit" <?= $products===[]?'disabled':'' ?>>Complete sale</button><a class="button secondary" href="<?= site_url('dashboard') ?>">Cancel</a></div></form></section>
<?= $this->endSection() ?>
