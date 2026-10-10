<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<p class="eyebrow">Staff form</p>
<h1><?= esc($heading) ?></h1>
<p class="lead">Enter the account details below. Usernames must be unique.</p>

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

    <form action="<?= esc($formAction, 'attr') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="form-grid">
            <div class="field">
                <label for="username">Username *</label>
                <input id="username" name="username" type="text" maxlength="50" value="<?= esc(old('username', $user['username'] ?? ''), 'attr') ?>" required>
            </div>
            <div class="field">
                <label for="full_name">Full name *</label>
                <input id="full_name" name="full_name" type="text" maxlength="100" value="<?= esc(old('full_name', $user['full_name'] ?? ''), 'attr') ?>" required>
            </div>
            <div class="field">
                <label for="password">Password<?= $isEdit ? '' : ' *' ?></label>
                <input id="password" name="password" type="password" minlength="8" autocomplete="new-password"<?= $isEdit ? '' : ' required' ?>>
                <?php if ($isEdit): ?><p class="hint">Leave blank to keep the current password.</p><?php endif ?>
            </div>
            <div class="field">
                <label for="password_confirm">Confirm password<?= $isEdit ? '' : ' *' ?></label>
                <input id="password_confirm" name="password_confirm" type="password" minlength="8" autocomplete="new-password"<?= $isEdit ? '' : ' required' ?>>
            </div>

                <?php $currentAvatar = ($user['avatar'] ?? null) ?: 'placeholder.svg'; ?>
                <div class="field full">
                    <label for="avatar">Profile picture</label>
                    <?php if ($isEdit): ?>
                    <div class="current-avatar">
                        <img class="avatar" src="<?= base_url('uploads/avatars/' . rawurlencode($currentAvatar)) ?>" alt="Current profile picture">
                        <span class="hint">Current profile picture</span>
                    </div>
                    <?php endif ?>
                    <input id="avatar" name="avatar" type="file" accept="image/jpeg,image/png">
                    <p class="hint">JPG or PNG only. Maximum file size: 2 MB. The image will be prepared as a 240 × 240 thumbnail.</p>
                </div>
        </div>
        <div class="form-actions">
            <button class="button" type="submit">Save staff account</button>
            <a class="button secondary" href="<?= site_url('users') ?>">Cancel</a>
        </div>
    </form>
</section>
<?= $this->endSection() ?>
