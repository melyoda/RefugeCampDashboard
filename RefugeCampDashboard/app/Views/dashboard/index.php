<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">

    <!-- Top Meta Action Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 text-dark fw-bold mb-1">Camp Operational Hub</h1>
            <p class="text-muted small mb-0">Live census and relief distribution overview tracking metrics.</p>
        </div>

        <!-- Safe Financial Mini-Row Widget -->
        <div class="bg-white border rounded p-2 px-3 shadow-sm d-flex align-items-center gap-3">
            <small class="text-muted fw-bold text-uppercase small">Safe Balance Buffer:</small>
            <span class="fw-bold fs-5 <?= $net_balance >= 0 ? 'text-success' : 'text-danger' ?>">
                <?= number_format($net_balance, 2) ?> <small class="text-muted fs-6">CUR</small>
            </span>
        </div>
    </div>

    <!-- 1. CENSUS & PHYSICAL RESOURCE CARDS GRID -->
    <div class="row g-3 mb-4">

        <!-- Headcount Metrics -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100 bg-dark text-white">
                <div class="card-body">
                    <div class="text-muted-light small fw-bold text-uppercase mb-1">Site Census Population</div>
                    <h2 class="fw-bold mb-1 display-6"><?= number_format($total_headcount) ?></h2>
                    <div class="small">
                        <span class="badge bg-light text-dark"><?= $active_adults ?> Adults</span>
                        <span class="badge bg-secondary"><?= $active_children ?> Kids</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Water Logs -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100 bg-white">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase mb-1">🚰 Water Allocations</div>
                    <h2 class="fw-bold text-dark mb-1 display-6"><?= $water_deliveries ?></h2>
                    <div class="small text-muted">Truck deliveries / infrastructure items logged</div>
                </div>
            </div>
        </div>

        <!-- Food Supplies -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100 bg-white">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Boxed Food Crates</div>
                    <h2 class="fw-bold text-dark mb-1 display-6"><?= $food_distributed ?></h2>
                    <div class="small text-muted">Bulk or family food drops verified</div>
                </div>
            </div>
        </div>

        <!-- Cleanliness Packs -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100 bg-white">
                <div class="card-body">
                    <div class="text-muted small fw-bold text-uppercase mb-1">🧼 Hygiene Drop Logs</div>
                    <h2 class="fw-bold text-dark mb-1 display-6"><?= $hygiene_distributed ?></h2>
                    <div class="small text-muted">Cleaning and sanitation lots cataloged</div>
                </div>
            </div>
        </div>

    </div>

    <!-- 2. QUICK ACTIONS CONTROL COMMAND LINKS -->
    <div class="card shadow-sm border-0 mb-4 bg-light border">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="small fw-bold text-dark text-uppercase"><i class="bi bi-lightning-fill text-warning"></i> Quick Registry Actions:</div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= base_url('residents/create') ?>" class="btn btn-sm btn-outline-primary">+ New Resident Enrollment</a>
                    <a href="<?= base_url('activities/create') ?>" class="btn btn-sm btn-outline-dark">+ Log Material Supply / Expense</a>
                    <a href="<?= base_url('donations/create') ?>" class="btn btn-sm btn-outline-success">+ Record Funding Deposit</a>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. FEED ROW LAYOUTS -->
    <div class="row g-4">
        
        <!-- Left Ticker: Recent Actions Layout -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 h6 fw-bold text-dark">Recent Operations Feed</h5>
                    <a href="<?= base_url('activities') ?>" class="text-primary small text-decoration-none">Review Entire Log</a>
                </div>
                <div class="table-responsive bg-white">
                    <table class="table table-hover align-middle mb-0 small">
                        <tbody>
                            <?php if (!empty($recent_activities)): foreach ($recent_activities as $act): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">
                                            <?= esc($act['title']) ?>
                                            <?php if ($act['is_distributed_aid']): ?>
                                                <span class="badge bg-info text-dark ms-1 small" style="font-size: 0.7rem;">Aid Pack</span>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-muted">
                                            <?= date('M d, Y', strtotime($act['created_at'])) ?>
                                            <?= $act['aid_category'] ? ' • ' . esc($act['aid_category']) : '' ?>
                                        </small>
                                    </td>
                                    <td class="text-end fw-bold text-muted"><?= number_format($act['cost'], 2) ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td class="text-center text-muted py-3">No activity items recorded.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Ticker: Arrivals Stream -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 h6 fw-bold text-dark">Recent Camp Enrollments</h5>
                    <a href="<?= base_url('residents') ?>" class="text-primary small text-decoration-none">View Full Roster</a>
                </div>
                <div class="table-responsive bg-white">
                    <table class="table table-hover align-middle mb-0 small">
                        <tbody>
                            <?php if (!empty($recent_residents)): foreach ($recent_residents as $res): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?= esc($res['full_name']) ?></div>
                                        <small class="text-muted">Marital Registry: <?= esc($res['marital_status'] ?? '—') ?></small>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-light text-dark border"><?= $res['children_count'] ?> Dependent Kids</span>
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