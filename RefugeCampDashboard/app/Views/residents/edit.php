<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Modify Profile: <?= esc($resident['full_name']) ?></h5>
                    <a href="<?= base_url('residents') ?>" class="btn btn-sm btn-outline-light">Back to Registry</a>
                </div>
                <div class="card-body bg-light">

                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('residents/update/' . $resident['id']) ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label fw-bold">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" value="<?= old('first_name', $resident['first_name']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label fw-bold">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" value="<?= old('last_name', $resident['last_name']) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="full_name" class="form-label fw-bold">Complete Full Name <span class="text-muted small">(Include full 3-4 names or tribal designations)</span></label>
                            <input type="text" name="full_name" id="full_name" class="form-control" value="<?= old('full_name', $resident['full_name']) ?>" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="primary_phone" class="form-label">Primary Phone Number</label>
                                <input type="text" name="primary_phone" id="primary_phone" class="form-control" value="<?= old('primary_phone', $resident['primary_phone']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="backup_phone" class="form-label">Backup Phone Number <span class="text-muted small">(Optional)</span></label>
                                <input type="text" name="backup_phone" id="backup_phone" class="form-control" value="<?= old('backup_phone', $resident['backup_phone']) ?>">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="marital_status" class="form-label">Family Head Status <span class="text-muted small">(Optional)</span></label>
                                <input type="text" name="marital_status" id="marital_status" class="form-control" value="<?= old('marital_status', $resident['marital_status']) ?>" placeholder="e.g. Single, Married, Married to multiple wives">
                            </div>
                            <div class="col-md-6">
                                <label for="children_count" class="form-label">Number of Children <span class="text-muted small">(Optional)</span></label>
                                <input type="number" min="0" name="children_count" id="children_count" class="form-control" value="<?= old('children_count', $resident['children_count']) ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">General Profile Notes / Directives</label>
                            <textarea name="notes" id="notes" class="form-control" rows="4"><?= old('notes', $resident['notes']) ?></textarea>
                        </div>

                        <div class="mb-3 card p-3 border-0 shadow-sm">
                            <label class="form-label d-block fw-bold">Camp Attendance Operational State</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" <?= old('is_active', $resident['is_active']) == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label ms-2" for="is_active">
                                    Resident is actively staying at the camp site location
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?= base_url('residents') ?>" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Update Profile Record</button>
                        </div>
                    </form>

                    <!-- Placement: Right underneath the existing form container inside edit.php -->
                    <div class="row mt-4">
                        <div class="col-md-8 mx-auto">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0">Individual Distribution & Intervention History</h6>
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
                                                    <td>$<?= number_format($h['cost'], 2) ?> USD</td>
                                                </tr>
                                            <?php endforeach; else: ?>
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted py-3">This resident has not been explicitly linked to any individual ledger distributions yet.</td>
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
        </div>
    </div>
</div>
<?= $this->endSection() ?>