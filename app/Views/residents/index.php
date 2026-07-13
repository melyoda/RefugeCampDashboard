<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success_modal')) : ?>
    <?php $modalData = session()->getFlashdata('success_modal'); ?>

    <div class="modal fade" id="enrollmentSuccessModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="enrollmentSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold h6" id="enrollmentSuccessModalLabel">🎉 Direct Enrollment Successful</h5>
                </div>
                <div class="modal-body bg-white p-4 text-center">
                    <p class="text-muted small mb-3">
                        A profile has been created for <strong><?= esc($modalData['name']) ?></strong> (ID: <?= esc($modalData['id']) ?>).
                    </p>

                    <div class="p-3 rounded mb-3 border" style="background-color: #f8f9fa;">
                        <span class="text-muted d-block small font-monospace uppercase">RESIDENT PORTAL ACCESS CODE</span>
                        <strong class="fs-3 text-primary font-monospace d-block my-2 tracking-wider"><?= esc($modalData['code']) ?></strong>
                        <button type="button" class="btn btn-sm btn-outline-secondary px-3" onclick="navigator.clipboard.writeText('<?= esc($modalData['code']) ?>'); alert('Access code copied to clipboard!');">
                            📋 Copy Code
                        </button>
                    </div>

                    <div class="alert alert-warning border-0 small text-start mb-0">
                        <strong>⚠️ Notice for Administration:</strong> Please copy down or print this code layout right now. For structural safety, this credential is cryptographically hidden and **cannot be retrieved or viewed again** once this window is closed.
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-sm btn-dark px-4 fw-bold" data-bs-dismiss="modal">I Have Saved The Code</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var successModal = new bootstrap.Modal(document.getElementById('enrollmentSuccessModal'));
            successModal.show();
        });
    </script>
<?php endif; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 text-dark mb-0">Residents Registry</h2>
        <a href="<?= base_url('residents/create') ?>" class="btn btn-primary btn-sm">+ Enroll Resident</a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success shadow-sm"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger shadow-sm"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-4 bg-white">
        <div class="card-body p-3">
            <div class="row g-2">
                <div class="col-md-9">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted"><i class="bi bi-search">🔍</i></span>
                        <input type="text" id="registrySearchInput" class="form-control form-control-sm" placeholder="Search by name, passport/national ID, or primary phone line...">
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 fw-bold" onclick="clearSearchFilter()">Clear Filters</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom-0 pt-3">
            <ul class="nav nav-tabs card-header-tabs" id="registryTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active text-dark fw-bold small pb-2" id="active-tab" data-bs-toggle="tab" data-bs-target="#active-pane" type="button" role="tab" aria-controls="active-pane" aria-selected="true">
                        🟢 Active Roster
                        <span class="badge bg-success ms-1 small"><?= count($active_residents) ?></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-dark fw-bold small pb-2" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-pane" type="button" role="tab" aria-controls="pending-pane" aria-selected="false">
                        🟡 Pending Verification
                        <span class="badge <?= count($pending_residents) > 0 ? 'bg-danger' : 'bg-secondary' ?> ms-1 small"><?= count($pending_residents) ?></span>
                    </button>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="registryTabsContent">

            <div class="tab-pane fade show active" id="active-pane" role="tabpanel" aria-labelledby="active-tab" tabindex="0">
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
                            <?php if (!empty($active_residents)): foreach ($active_residents as $r): ?>
                                <tr class="resident-row-item">
                                    <td class="font-monospace fw-bold small search-target">
                                        <?= esc($r['document_id'] ?: '—') ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold search-target"><?= esc($r['full_name']) ?></div>
                                        <small class="text-muted search-target"><?= esc($r['last_name']) ?>, <?= esc($r['first_name']) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Active</span>
                                    </td>
                                    <td>
                                        <div class="small"><strong>P:</strong> <span class="search-target"><?= esc($r['primary_phone'] ?? '—') ?></span></div>
                                        <?php if (!empty($r['backup_phone'])): ?>
                                            <div class="small text-muted"><strong>B:</strong> <span class="search-target"><?= esc($r['backup_phone']) ?></span></div>
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
                                    <td colspan="6" class="text-center text-muted py-4">No active verification records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="pending-pane" role="tabpanel" aria-labelledby="pending-tab" tabindex="0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Registration ID</th>
                                <th>Full Name</th>
                                <th>Contacts</th>
                                <th>Family Head Details</th>
                                <th class="text-end">Triage Decision Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pending_residents)): foreach ($pending_residents as $p): ?>
                                <tr class="resident-row-item">
                                    <td class="font-monospace fw-bold small search-target">
                                        <?= esc($p['document_id'] ?: '—') ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-warning-dark search-target"><?= esc($p['full_name']) ?></div>
                                        <small class="text-muted search-target"><?= esc($p['last_name']) ?>, <?= esc($p['first_name']) ?></small>
                                    </td>
                                    <td>
                                        <div class="small"><strong>P:</strong> <span class="search-target"><?= esc($p['primary_phone'] ?? '—') ?></span></div>
                                    </td>
                                    <td class="small">
                                        <div><strong>DOB:</strong> <?= esc($p['dob'] ?? '—') ?></div>
                                        <div><strong>Status:</strong> <?= esc($p['marital_status'] ?? '—') ?></div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <form action="<?= base_url('residents/approve/' . $p['id']) ?>" method="POST" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-success px-3 fw-bold">Admit</button>
                                            </form>
                                            <form action="<?= base_url('residents/reject/' . $p['id']) ?>" method="POST" onsubmit="return confirm('Permanently reject and delete this application request?');" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-danger px-3">Reject</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Clear skies! No pending self-registrations waiting in the triage queue.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById('registrySearchInput').addEventListener('keyup', function() {
    let filterVal = this.value.toLowerCase().trim();
    let rows = document.querySelectorAll('.resident-row-item');

    rows.forEach(row => {
        let textContentMatch = false;
        let targets = row.querySelectorAll('.search-target');

        targets.forEach(target => {
            if (target.textContent.toLowerCase().includes(filterVal)) {
                textContentMatch = true;
            }
        });

        if (textContentMatch) {
            row.style.setProperty('display', '', 'important');
        } else {
            row.style.setProperty('display', 'none', 'important');
        }
    });
});

function clearSearchFilter() {
    let input = document.getElementById('registrySearchInput');
    input.value = '';
    let rows = document.querySelectorAll('.resident-row-item');
    rows.forEach(row => {
        row.style.setProperty('display', '', 'important');
    });
}
</script>

<?= $this->endSection() ?>