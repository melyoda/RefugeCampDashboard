<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 text-dark mb-0">Inbound Funding Ledger</h2>
        <a href="<?= base_url('donations/create') ?>" class="btn btn-success btn-sm">+ Log Received Donation</a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Date Received</th>
                        <th>Funding Source / Donor</th>
                        <th>Amount (USD)</th>
                        <th>Notes / Context</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($donations)): foreach ($donations as $d): ?>
                        <tr>
                            <td class="fw-bold"><?= esc($d['donation_date']) ?></td>
                            <td><?= esc($d['donor_name']) ?></td>
                            <td class="text-success fw-bold">+$<?= number_format($d['amount'], 2) ?></td>
                            <td class="text-muted small"><?= esc($d['notes'] ?? '—') ?></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No inbound donation logs found in the database.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>