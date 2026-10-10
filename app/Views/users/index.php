<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div>
        <p class="eyebrow">Team</p>
        <h1>Staff Accounts</h1>
        <p class="lead">Manage secure staff profiles, usernames, passwords, and avatars.</p>
    </div>
    <a class="button" href="<?= site_url('users/new') ?>">＋ New staff member</a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Full name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <?php
                        $avatar = ! empty($user['avatar'])
                            && is_file(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR . $user['avatar'])
                            ? $user['avatar']
                            : 'placeholder.svg';
                    ?>
                    <td>
                        <div class="person">
                            <img class="avatar" src="<?= base_url('uploads/avatars/' . rawurlencode($avatar)) ?>" alt="<?= esc($user['full_name']) ?> avatar">
                            <strong><?= esc($user['username']) ?></strong>
                        </div>
                    </td>
                    <td><?= esc($user['full_name']) ?></td>
                    <td><div class="actions-inline"><a class="button secondary small" href="<?= site_url('users/' . $user['id'] . '/edit') ?>">Edit</a><?php if ((int) $user['id'] !== (int) session('userId')): ?><form action="<?= site_url('users/' . $user['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Delete this staff account?')"><?= csrf_field() ?><button class="button danger small" type="submit">Delete</button></form><?php endif ?></div></td>
                </tr>
            <?php endforeach ?>
            <?php if ($users === []): ?>
                <tr><td class="empty-state" colspan="3">No user accounts yet.</td></tr>
            <?php endif ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
