<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>
Activities Log
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Logged Camp Activities
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">A historical ledger of all operations and expenditure entries logged for this camp.</p>
    <a href="<?= base_url('activities/create') ?>" class="btn btn-primary btn-sm">Log New Activity</a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success py-2 small">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3" style="width: 15%">Date</th>
                        <th style="width: 35%">Activity Title & Description</th>
                        <th style="width: 15%">Cost (USD)</th>
                        <th style="width: 15%">Receipt</th>
                        <th class="text-end pe-3" style="width: 20%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($activities)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted small">No activities logged yet. Click "Log New Activity" to start.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($activities as $activity): ?>
                            <tr onclick="window.location='<?= base_url('activities/show/' . $activity['id']) ?>';" style="cursor: pointer;">
                                <td class="ps-3 small text-muted">
                                    <?= date('M d, Y H:i', strtotime($activity['created_at'])) ?>
                                </td>
                                <td>
                                    <strong class="d-block text-dark small"><?= esc($activity['title']) ?></strong>
                                    <span class="text-muted extra-small d-block text-truncate" style="max-width: 300px;">
                                        <?= esc($activity['description']) ?: 'No description provided.' ?>
                                    </span>
                                </td>
                                <td class="fw-bold text-success small">
                                    $<?= number_format($activity['cost'], 2) ?>
                                </td>
                                <td>
                                    <?php if ($activity['receipt_path']): ?>
                                        <a href="<?= base_url($activity['receipt_path']) ?>" target="_blank" class="badge bg-info text-dark text-decoration-none extra-small">
                                            👁️ View Receipt
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted extra-small">None</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="<?= base_url('activities/edit/' . $activity['id']) ?>" class="btn btn-outline-secondary btn-sm extra-small py-1">Edit</a>
                                   <form action="<?= base_url('activities/delete/' . $activity['id']) ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this activity?')">
                                        <?= csrf_field() ?> <button type="submit" class="btn btn-outline-danger btn-sm extra-small py-1">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>