<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div>
        <p class="eyebrow">Relationships</p>
        <h1>Customers</h1>
        <p class="lead">Create, review, update, and remove customer contact information.</p>
    </div>
    <a class="button" href="<?= site_url('customers/new') ?>">+ New customer</a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Full name</th>
                <th>Email address</th>
                <th>Phone number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><strong><?= esc($customer['full_name']) ?></strong></td>
                    <td><?= esc($customer['email']) ?></td>
                    <td><?= esc($customer['phone']) ?></td>
                    <td><div class="actions-inline"><a class="button secondary small" href="<?= site_url('customers/' . $customer['id'] . '/edit') ?>">Edit</a><form action="<?= site_url('customers/' . $customer['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Delete this customer?')"><?= csrf_field() ?><button class="button danger small" type="submit">Delete</button></form></div></td>
                </tr>
            <?php endforeach ?>
            <?php if ($customers === []): ?>
                <tr><td class="empty-state" colspan="4">No customer accounts yet.</td></tr>
            <?php endif ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
