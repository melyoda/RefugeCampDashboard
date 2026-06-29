<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Enroll New Resident Profile</h5>
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

                    <form action="<?= base_url('residents/store') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label fw-bold">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" value="<?= old('first_name') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label fw-bold">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" value="<?= old('last_name') ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="full_name" class="form-label fw-bold">Complete Full Name <span class="text-muted small">(Include full 3-4 names or tribal designations)</span></label>
                            <input type="text" name="full_name" id="full_name" class="form-control" value="<?= old('full_name') ?>" placeholder="e.g. Full generational sequence name" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="primary_phone" class="form-label">Primary Phone Number</label>
                                <input type="text" name="primary_phone" id="primary_phone" class="form-control" value="<?= old('primary_phone') ?>" placeholder="Main contact">
                            </div>
                            <div class="col-md-6">
                                <label for="backup_phone" class="form-label">Backup Phone Number <span class="text-muted small">(Optional)</span></label>
                                <input type="text" name="backup_phone" id="backup_phone" class="form-control" value="<?= old('backup_phone') ?>" placeholder="Secondary/Relative">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="marital_status" class="form-label">Family Head Status <span class="text-muted small">(Optional)</span></label>
                                <input type="text" name="marital_status" id="marital_status" class="form-control" value="<?= old('marital_status') ?>" placeholder="e.g. Single, Married, Married to multiple wives">
                            </div>
                            <div class="col-md-6">
                                <label for="children_count" class="form-label">Number of Children <span class="text-muted small">(Optional)</span></label>
                                <input type="number" min="0" name="children_count" id="children_count" class="form-control" value="<?= old('children_count', 0) ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">General Profile Notes / Directives</label>
                            <textarea name="notes" id="notes" class="form-control" rows="4"><?= old('notes') ?></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?= base_url('residents') ?>" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Complete Enrollment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>