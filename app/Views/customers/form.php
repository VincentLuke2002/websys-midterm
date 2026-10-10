<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<p class="eyebrow">Customer form</p>
<h1><?= esc($heading) ?></h1>
<p class="lead">Enter the customer details below. Required fields are marked with an asterisk.</p>

<section class="form-card">
    <?php if ($errors = session('errors')): ?>
        <div class="errors" role="alert">
            <strong>Please check the form.</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <form action="<?= esc($formAction, 'attr') ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-grid">
            <div class="field full">
                <label for="full_name">Full name *</label>
                <input id="full_name" name="full_name" type="text" maxlength="100" value="<?= esc(old('full_name', $customer['full_name'] ?? ''), 'attr') ?>" required>
            </div>
            <div class="field">
                <label for="email">Email address *</label>
                <input id="email" name="email" type="email" maxlength="100" value="<?= esc(old('email', $customer['email'] ?? ''), 'attr') ?>" required>
            </div>
            <div class="field">
                <label for="phone">Phone number</label>
                <input id="phone" name="phone" type="text" maxlength="20" value="<?= esc(old('phone', $customer['phone'] ?? ''), 'attr') ?>">
            </div>
        </div>
        <div class="form-actions">
            <button class="button" type="submit">Save customer</button>
            <a class="button secondary" href="<?= site_url('customers') ?>">Cancel</a>
        </div>
    </form>
</section>
<?= $this->endSection() ?>

