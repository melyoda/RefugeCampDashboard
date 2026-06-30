<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">

    <div class="mb-4">
        <h1 class="h3 text-dark fw-bold mb-1">Operational Control Panel</h1>
        <p class="text-muted small">Live metrics aggregated from the active camp database engine.</p>
    </div>

    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body bg-white rounded">
                    <div class="text-muted small fw-bold text-uppercase tracking-wider mb-1">Active Site Census</div>
                    <h2 class="display-6 fw-bold text-dark mb-1"><?= number_format($total_headcount) ?></h2>
                    <div class="small text-muted">
                        <span class="badge bg-dark"><?= $active_adults ?> Adults</span>
                        <span class="badge bg-secondary"><?= $active_children ?> Children</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body bg-white rounded">
                    <div class="text-muted small fw-bold text-uppercase tracking-wider mb-1">Net Financial Balance</div>
                    <h2 class="display-6 fw-bold <?= $net_balance >= 0 ? 'text-success' : 'text-danger' ?> mb-1">
                        $<?= number_format($net_balance, 2) ?>
                    </h2>
                    <div class="small text-muted">Total remaining operating capital</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body bg-white rounded">
                    <div class="text-muted small fw-bold text-uppercase tracking-wider mb-1">Monthly Expenses</div>
                    <h2 class="display-6 fw-bold text-dark mb-1">$<?= number_format($monthly_expenses, 2) ?></h2>
                    <div class="small text-muted">Aggregated outlays (Last 30 Days)</div>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow-sm border-0 mb-4 bg-dark text-white">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="small fw-bold text-uppercase text-muted-light"><i class="bi bi-lightning-fill"></i> Command Shortcuts:</div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= base_url('residents/create') ?>" class="btn btn-sm btn-primary">+ Enroll Resident</a>
                    <a href="<?= base_url('activities/create') ?>" class="btn btn-sm btn-light">+ Log Expense</a>
                    <a href="<?= base_url('donations/create') ?>" class="btn btn-sm btn-success">+ Record Funding</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 h6 fw-bold text-dark">Recent Operations Log</h5>
                    <a href="<?= base_url('activities') ?>" class="text-primary small text-decoration-none">View All</a>
                </div>
                <div class="table-responsive bg-white">
                    <table class="table table-hover align-middle mb-0 small">
                        <tbody>
                            <?php if (!empty($recent_activities)): foreach ($recent_activities as $act): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?= esc($act['title']) ?></div>
                                        <small class="text-muted"><?= date('M d, Y', strtotime($act['created_at'])) ?></small>
                                    </td>
                                    <td class="text-end fw-bold text-secondary">-$<?= number_format($act['cost'], 2) ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td class="text-center text-muted py-3">No expenses logged.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 h6 fw-bold text-dark">Recent Enrollments</h5>
                    <a href="<?= base_url('residents') ?>" class="text-primary small text-decoration-none">View All</a>
                </div>
                <div class="table-responsive bg-white">
                    <table class="table table-hover align-middle mb-0 small">
                        <tbody>
                            <?php if (!empty($recent_residents)): foreach ($recent_residents as $res): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?= esc($res['full_name']) ?></div>
                                        <small class="text-muted">Head of Family Status: <?= esc($res['marital_status'] ?? 'Not Specified') ?></small>
                                    </td>
                                    <td class="text-end text-muted">
                                        <span class="badge bg-light text-dark border"><?= $res['children_count'] ?> Kids</span>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td class="text-center text-muted py-3">No residents registered yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
<?= $this->endSection() ?>