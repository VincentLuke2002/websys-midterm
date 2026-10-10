<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-heading"><div><p class="eyebrow">Inventory form</p><h1><?= esc($heading) ?></h1><p class="lead">Enter the product details and optionally upload a display image.</p></div></div>
<section class="form-card">
<?php if ($errors=session('errors')): ?><div class="errors"><strong>Please check the form.</strong><ul><?php foreach($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul></div><?php endif ?>
<form action="<?= esc($formAction,'attr') ?>" method="post" enctype="multipart/form-data"><?= csrf_field() ?><div class="form-grid">
<div class="field full"><label for="name">Product name *</label><input id="name" name="name" maxlength="100" value="<?= esc(old('name',$product['name']??''),'attr') ?>" required></div>
<div class="field"><label for="price">Price (PHP) *</label><input id="price" name="price" type="number" min="0" step="0.01" value="<?= esc(old('price',$product['price']??''),'attr') ?>" required></div>
<div class="field"><label for="stock_quantity">Stock quantity *</label><input id="stock_quantity" name="stock_quantity" type="number" min="0" step="1" value="<?= esc(old('stock_quantity',$product['stock_quantity']??0),'attr') ?>" required></div>
<div class="field full"><label for="image">Product image</label><?php if(!empty($product['image'])): ?><div class="current-image"><img src="<?= base_url('uploads/products/'.rawurlencode($product['image'])) ?>" alt="Current product"><span class="hint">Current product image</span></div><?php endif ?><input id="image" name="image" type="file" accept="image/jpeg,image/png"><p class="hint">JPG or PNG, maximum 2 MB. Images are cropped to 640 × 480.</p></div>
</div><div class="form-actions"><button class="button" type="submit">Save product</button><a class="button secondary" href="<?= site_url('products') ?>">Cancel</a></div></form></section>
<?= $this->endSection() ?>
