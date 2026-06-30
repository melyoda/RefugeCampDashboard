<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 text-dark mb-0">Residents Registry</h2>
        <a href="<?= base_url('residents/create') ?>" class="btn btn-primary btn-sm">+ Enroll Resident</a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Registration ID</th>
                        <th>Full Name</th>
                        <th>Status</th>
                        <th>Contacts</th>
                        <th>Family Head Details</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($residents)): foreach ($residents as $r): ?>
                        <tr>
                            <td class="font-monospace fw-bold small">
                                <?= esc($r['document_id'] ?: '—') ?>
                            </td>
                            <td>
                                <div class="fw-bold"><?= esc($r['full_name']) ?></div>
                                <small class="text-muted"><?= esc($r['last_name']) ?>, <?= esc($r['first_name']) ?></small>
                            </td>
                            <td>
                                <?php if ($r['is_active'] == 1): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive / Left</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="small"><strong>P:</strong> <?= esc($r['primary_phone'] ?? '—') ?></div>
                                <?php if (!empty($r['backup_phone'])): ?>
                                    <div class="small text-muted"><strong>B:</strong> <?= esc($r['backup_phone']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="small">
                                <div><strong>Status:</strong> <?= esc($r['marital_status'] ?? '—') ?></div>
                                <div><strong>Children:</strong> <?= esc($r['children_count'] ?? 0) ?></div>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="<?= base_url('residents/edit/' . $r['id']) ?>" class="btn btn-sm btn-outline-primary">Edit</a>

                                    <form action="<?= base_url('residents/delete/' . $r['id']) ?>" method="POST" onsubmit="return confirm('Archive this profile? System data configurations will retain ledger histories.');" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Archive</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No resident records found in the engine database.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>