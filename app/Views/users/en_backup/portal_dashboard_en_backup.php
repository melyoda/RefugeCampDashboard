<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Profile Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container">
        <span class="navbar-brand fw-bold">⛺ Household Portal</span>
        <div class="d-flex align-items-center">
            <span class="text-light small me-3 d-none d-sm-inline">Welcome, <strong><?= esc($family_head['full_name']) ?></strong></span>
            <a href="<?= base_url('household/logout') ?>" class="btn btn-sm btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container py-4">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success shadow-sm py-2"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white fw-bold">Family Head Profile</div>
                <div class="card-body small">
                    <div class="mb-2"><strong>Registration ID:</strong> <span class="font-monospace fw-bold text-primary"><?= esc($family_head['document_id']) ?></span></div>
                    <div class="mb-2"><strong>Full Name:</strong> <?= esc($family_head['full_name']) ?></div>
                    <div class="mb-2"><strong>Date of Birth:</strong> <?= esc($family_head['dob']) ?></div>
                    <div class="mb-2"><strong>Primary Phone:</strong> <?= esc($family_head['primary_phone']) ?></div>
                    <div class="mb-2"><strong>Backup Phone:</strong> <?= esc($family_head['backup_phone'] ?: '—') ?></div>
                    <div class="mb-2"><strong>Marital Status:</strong> <?= esc($family_head['marital_status']) ?></div>
                    <hr>
                    <div class="mb-0">
                        <strong>Medical/Accessibility Needs:</strong><br>
                        <?php if ($family_head['has_disability']): ?>
                            <span class="badge bg-danger mt-1">Special Assistance Required</span>
                            <p class="text-muted mt-1 mb-0 bg-light p-2 rounded border"><?= esc($family_head['disability_details']) ?></p>
                        <?php else: ?>
                            <span class="text-muted">None disclosed.</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Household Spouses & Children Roster</span>
                    <button type="button" class="btn btn-sm btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#addMemberModal">+ Add Family Member</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>Relationship</th>
                                <th>Full Name</th>
                                <th>Gender</th>
                                <th>Date of Birth</th>
                                <th>Medical Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($dependents)): foreach ($dependents as $dep): ?>
                                <tr>
                                    <td>
                                        <span class="badge <?= $dep['relationship_type'] === 'Spouse' ? 'bg-info text-dark' : 'bg-secondary' ?>">
                                            <?= esc($dep['relationship_type']) ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold"><?= esc($dep['full_name']) ?></td>
                                    <td><?= esc($dep['gender']) ?></td>
                                    <td><?= esc($dep['dob']) ?></td>
                                    <td>
                                        <?php if ($dep['has_disability']): ?>
                                            <span class="text-danger border border-danger rounded px-1 d-inline-block small" title="<?= esc($dep['disability_details']) ?>"> Support Needed</span>
                                        <?php else: ?>
                                            <span class="text-muted">Normal</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <form action="<?= base_url('household/remove-member/' . $dep['id']) ?>" method="POST" onsubmit="return confirm('Remove this member from your active household roster?');" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No additional family members are currently attached to this household registry.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="w-100 mt-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                        <span>Your Distribution & Assistance Log</span>
                        <span class="badge bg-secondary font-monospace small">History Log</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th>Date Received</th>
                                    <th>Item / Activity Name</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($history)): foreach ($history as $log): ?>
                                    <tr>
                                        <td class="text-nowrap"><?= date('Y-m-d H:i', strtotime($log['created_at'])) ?></td>
                                        <td>
                                            <span class="fw-bold d-block"><?= esc($log['title']) ?></span>
                                            <?php if ($log['is_distributed_aid'] == 1): ?>
                                                <span class="badge bg-success py-0 px-2 mt-1" style="font-size: 0.75rem;">Distributed Aid</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="text-capitalize badge bg-light text-dark border"><?= esc($log['aid_category'] ?? 'General') ?></span>
                                        </td>
                                        <td class="text-muted"><?= esc($log['description'] ?: 'No details provided.') ?></td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No recorded assistance distributions or logged activity links found for your profile profile yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Household Dependent</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('household/add-member') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Relationship Type</label>
                        <select name="relationship_type" class="form-select form-select-sm" required>
                            <option value="Spouse">Spouse</option>
                            <option value="Child">Child</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Gender</label>
                        <select name="gender" class="form-select form-select-sm" required>
                            <option value="Female">Female</option>
                            <option value="Male">Male</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Full Name</label>
                        <input type="text" name="full_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Date of Birth</label>
                        <input type="date" name="dob" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch my-2">
                            <input class="form-check-input" type="checkbox" name="has_disability" value="1" id="depDisability" onchange="document.getElementById('depDisabilityDetails').classList.toggle('d-none', !this.checked)">
                            <label class="form-check-label small" for="depDisability">This person requires specialized medical/accessibility support</label>
                        </div>
                        <textarea name="disability_details" id="depDisabilityDetails" rows="2" class="form-control form-control-sm d-none" placeholder="Provide health details..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success px-3 fw-bold">Append Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>