<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="page-heading"><div><p class="eyebrow">Overview</p><h1>Good day, <?= esc(explode(' ', (string) session('fullName'))[0]) ?></h1><p class="lead">Here is what is happening with your store today.</p></div><a class="button" href="<?= site_url('sales/new') ?>">＋ Record a sale</a></div>
<section class="stat-grid">
    <article class="stat-card accent"><span class="stat-label">Today's sales</span><strong class="stat-value">₱<?= number_format($todayTotal, 2) ?></strong></article>
    <article class="stat-card"><span class="stat-label">Total transactions</span><strong class="stat-value"><?= number_format($saleCount) ?></strong></article>
    <article class="stat-card"><span class="stat-label">Products</span><strong class="stat-value"><?= number_format($productCount) ?></strong></article>
    <article class="stat-card"><span class="stat-label">Customers</span><strong class="stat-value"><?= number_format($customerCount) ?></strong></article>
</section>
<section class="quick-grid">
    <div class="panel"><div class="panel-head"><div><p class="eyebrow">Shortcuts</p><h2>Quick actions</h2></div></div><div class="quick-actions">
        <a class="quick-action" href="<?= site_url('sales/new') ?>"><strong>Record a sale</strong><small>Create a transaction and update stock.</small></a>
        <a class="quick-action" href="<?= site_url('products/new') ?>"><strong>Add a product</strong><small>Create a new item for your inventory.</small></a>
        <a class="quick-action" href="<?= site_url('customers/new') ?>"><strong>Add a customer</strong><small>Save new customer information.</small></a>
        <a class="quick-action" href="<?= site_url('sales') ?>"><strong>View sales history</strong><small>Review all completed transactions.</small></a>
    </div></div>
    <div class="panel"><div class="panel-head"><div><p class="eyebrow">Inventory</p><h2>Stock watch</h2></div></div><div class="stock-alert"><div><strong><?= number_format($lowStockCount) ?> low-stock item<?= $lowStockCount === 1 ? '' : 's' ?></strong><p class="hint">Five units or fewer remaining.</p></div><a class="button small secondary" href="<?= site_url('products') ?>">View</a></div></div>
</section>
<?= $this->endSection() ?>
