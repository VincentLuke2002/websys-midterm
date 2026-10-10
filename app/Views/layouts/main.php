<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="theme-color" content="#111827">
    <title><?= esc($title) ?> | Vertex POS</title>
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body>
<?php if (session('isLoggedIn')): ?>
<div class="app-shell">
    <aside class="sidebar">
        <a class="brand" href="<?= site_url('dashboard') ?>"><span class="brand-mark">V</span><span>Vertex <b>POS</b></span></a>
        <p class="nav-label">Workspace</p>
        <nav class="side-nav" aria-label="Main navigation">
            <a class="<?= url_is('dashboard') ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>"><span>◫</span> Dashboard</a>
            <a class="<?= url_is('products*') ? 'active' : '' ?>" href="<?= site_url('products') ?>"><span>□</span> Products</a>
            <a class="<?= url_is('customers*') ? 'active' : '' ?>" href="<?= site_url('customers') ?>"><span>○</span> Customers</a>
            <a class="<?= url_is('users*') ? 'active' : '' ?>" href="<?= site_url('users') ?>"><span>◇</span> Staff</a>
            <a class="<?= url_is('sales/new') ? 'active' : '' ?>" href="<?= site_url('sales/new') ?>"><span>＋</span> Record Sale</a>
            <a class="<?= url_is('sales') ? 'active' : '' ?>" href="<?= site_url('sales') ?>"><span>≡</span> Sales History</a>
        </nav>
        <div class="sidebar-user">
            <span class="user-initial"><?= esc(strtoupper(substr((string) session('fullName'), 0, 1))) ?></span>
            <div><strong><?= esc(session('fullName')) ?></strong><small>@<?= esc(session('username')) ?></small></div>
            <form action="<?= site_url('logout') ?>" method="post"><?= csrf_field() ?><button class="icon-button" type="submit" title="Log out" aria-label="Log out">↪</button></form>
        </div>
    </aside>
    <div class="main-panel">
        <header class="mobile-header"><a class="brand" href="<?= site_url('dashboard') ?>"><span class="brand-mark">V</span><span>Vertex <b>POS</b></span></a><span class="mobile-user"><?= esc(session('fullName')) ?></span></header>
        <main class="content">
            <?php if ($message = session()->getFlashdata('success')): ?><div class="alert success" role="status"><span>✓</span><?= esc($message) ?></div><?php endif ?>
            <?php if ($message = session()->getFlashdata('error')): ?><div class="alert error" role="alert"><span>!</span><?= esc($message) ?></div><?php endif ?>
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>
<?php else: ?>
<header class="public-header"><a class="brand dark" href="<?= site_url('/') ?>"><span class="brand-mark">V</span><span>Vertex <b>POS</b></span></a><nav><a href="<?= site_url('/') ?>">Home</a><a href="<?= site_url('about') ?>">About</a><a class="button small" href="<?= site_url('login') ?>">Staff login</a></nav></header>
<main class="public-main">
    <?php if ($message = session()->getFlashdata('success')): ?><div class="alert success" role="status"><span>✓</span><?= esc($message) ?></div><?php endif ?>
    <?php if ($message = session()->getFlashdata('error')): ?><div class="alert error" role="alert"><span>!</span><?= esc($message) ?></div><?php endif ?>
    <?= $this->renderSection('content') ?>
</main>
<?php endif ?>
</body></html>
