<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-9 mx-auto">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 text-dark mb-0">Modify Profile: <?= esc($resident['full_name']) ?></h2>
                <a href="<?= base_url('residents') ?>" class="btn btn-outline-secondary btn-sm">← Back to Registry</a>
            </div>

            <?php if (session()->getFlashdata('errors')) : ?>
                <div class="alert alert-danger shadow-sm">
                    <ul class="mb-0 small">
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('residents/update/' . $resident['id']) ?>" method="POST">
                <?= csrf_field() ?>

                <!-- SECTION 1: MAIN HEAD OF FAMILY RECORD -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white py-3">
                        <h5 class="mb-0 h6 fw-bold">Primary Family Head Demographics</h5>
                    </div>
                    <div class="card-body bg-white row g-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label small fw-bold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" id="first_name" class="form-control form-control-sm" value="<?= old('first_name', $resident['first_name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label small fw-bold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" id="last_name" class="form-control form-control-sm" value="<?= old('last_name', $resident['last_name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="document_id" class="form-label small fw-bold">Registration / Document ID <span class="text-danger">*</span></label>
                            <input type="text" name="document_id" id="document_id" class="form-control form-control-sm" value="<?= old('document_id', $resident['document_id'] ?? '') ?>" placeholder="e.g., National ID or Passport Number" required>
                        </div>
                        <div class="col-md-6">
                            <label for="dob" class="form-label small fw-bold">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="dob" id="dob" class="form-control form-control-sm" value="<?= old('dob', $resident['dob'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="primary_phone" class="form-label small fw-bold">Primary Phone <span class="text-danger">*</span></label>
                            <input type="text" name="primary_phone" id="primary_phone" class="form-control form-control-sm" value="<?= old('primary_phone', $resident['primary_phone']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="backup_phone" class="form-label small fw-bold">Secondary Backup Phone <span class="text-muted">(Optional)</span></label>
                            <input type="text" name="backup_phone" id="backup_phone" class="form-control form-control-sm" value="<?= old('backup_phone', $resident['backup_phone']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="marital_status" class="form-label small fw-bold">Marital Status <span class="text-danger">*</span></label>
                            <select name="marital_status" id="marital_status" class="form-select form-select-sm" required>
                                <option value="">Select...</option>
                                <option value="Single" <?= old('marital_status', $resident['marital_status']) === 'Single' ? 'selected' : '' ?>>Single</option>
                                <option value="Married" <?= old('marital_status', $resident['marital_status']) === 'Married' ? 'selected' : '' ?>>Married</option>
                                <option value="Widowed" <?= old('marital_status', $resident['marital_status']) === 'Widowed' ? 'selected' : '' ?>>Widowed</option>
                                <option value="Divorced" <?= old('marital_status', $resident['marital_status']) === 'Divorced' ? 'selected' : '' ?>>Divorced</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="children_count" class="form-label small fw-bold">Number of Children <span class="text-muted small">(Auto-tracked)</span></label>
                            <input type="number" min="0" name="children_count" id="children_count" class="form-control form-control-sm bg-light" value="<?= old('children_count', $resident['children_count']) ?>" readonly>
                        </div>
                        
                        <div class="col-12">
                            <label for="notes" class="form-label small fw-bold">General Profile Notes / Directives</label>
                            <textarea name="notes" id="notes" class="form-control form-control-sm" rows="3" placeholder="Administrative annotations..."><?= old('notes', $resident['notes']) ?></textarea>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch my-2">
                                <input class="form-check-input" type="checkbox" name="has_disability" value="1" id="headDisability" <?= old('has_disability', $resident['has_disability']) ? 'checked' : '' ?> onchange="document.getElementById('headDisabilityDetails').classList.toggle('d-none', !this.checked)">
                                <label class="form-check-label small fw-bold" for="headDisability">Head of family has a disability / requires medical care</label>
                            </div>
                            <textarea name="disability_details" id="headDisabilityDetails" rows="2" class="form-control form-control-sm <?= old('has_disability', $resident['has_disability']) ? '' : 'd-none' ?>" placeholder="Provide accessibility or medical specification tracking notes..."><?= old('disability_details', $resident['disability_details']) ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: OPERATIONAL STATUS FLAG -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body bg-white p-3">
                        <label class="form-label small d-block fw-bold mb-1">Camp Attendance Operational State</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" <?= old('is_active', $resident['is_active']) == 1 ? 'checked' : '' ?>>
                            <label class="form-check-label small ms-2 text-muted" for="is_active">
                                Resident registration is activated / processed for full camp infrastructure access
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="<?= base_url('residents') ?>" class="btn btn-sm btn-outline-secondary px-4">Cancel</a>
                    <button type="submit" class="btn btn-sm btn-primary px-4 fw-bold">Update Profile Record</button>
                </div>
            </form>

            <!-- SECTION 3: LEDGER AND TRACKING HISTORY -->
            <div class="card shadow-sm border-0 mt-5">
                <div class="card-header bg-secondary text-white py-2">
                    <h6 class="mb-0 text-white small fw-bold">Individual Distribution & Intervention History</h6>
                </div>
                <div class="table-responsive bg-white">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>Date Attached</th>
                                <th>Activity Log Item</th>
                                <th>Cost Share Metric</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($history)): foreach ($history as $h): ?>
                                <tr>
                                    <td><?= date('Y-m-d H:i', strtotime($h['created_at'])) ?></td>
                                    <td class="fw-bold text-dark"><?= esc($h['title']) ?></td>
                                    <td>**180°C**</td>
                                    <td>$<?= number_format($h['cost'], 2) ?> USD</td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">This resident has not been explicitly linked to any individual ledger distributions yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>